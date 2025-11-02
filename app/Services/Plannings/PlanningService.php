<?php

namespace App\Services\Plannings;

use App\Repositories\Plannings\PlanningRepository;
use App\Models\Planning;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

/**
 * Service métier pour planning : création, MAJ, suppression, comptage, export PDF.
 */
class PlanningService
{
  public function __construct(protected PlanningRepository $repo)
  {
  }

  public function getAll(?int $siteId = null, ?string $date = null)
  {
    return $this->repo->getAll($siteId, $date);
  }

  /**
   * Crée un planning et génère les attendances correspondantes.
   *
   * @param array $data
   * @return Planning
   */
  public function create(array $data): Planning
  {
    $data['created_by'] = $data['created_by'] ?? auth()->id();
    Log::channel('planning')->info('create.start', ['site' => $data['site_id'], 'date' => $data['date'], 'by' => $data['created_by']]);

    $planning = $this->repo->createWithAgents($data);

    // Générer les lignes d'attendance (comptabilisation) à partir des entrées
    $att = $this->repo->createAttendancesFromPlanning($planning);
    Log::channel('planning')->info('create.attendances', ['count' => count($att), 'planning' => $planning->id]);

    Log::channel('planning')->info('create.success', ['planning' => $planning->id]);
    return $planning;
  }

  /**
   * Met à jour un planning + attendances.
   */
  public function update(Planning $planning, array $data): Planning
  {
    Log::channel('planning')->info('update.start', ['planning' => $planning->id]);
    $p = $this->repo->updateWithAgents($planning, $data);

    // régénérer les attendances (updateOrCreate inside repo)
    $att = $this->repo->createAttendancesFromPlanning($p);
    Log::channel('planning')->info('update.attendances', ['count' => count($att), 'planning' => $p->id]);

    Log::channel('planning')->info('update.success', ['planning' => $p->id]);
    return $p;
  }

  /**
   * Supprime un planning.
   */
  public function delete(Planning $planning): bool
  {
    Log::channel('planning')->warning('delete.start', ['planning' => $planning->id]);
    $res = $this->repo->delete($planning);
    Log::channel('planning')->warning('delete.end', ['planning' => $planning->id, 'deleted' => $res]);
    return $res;
  }

  /**
   * Compte les jours de travail / absence / repos / permutation pour chaque agent d'un site.
   *
   * @param int $siteId
   * @param Carbon|string $start
   * @param Carbon|string $end
   * @return array keyed by agent_id => ['worked'=>int,'absent'=>int,'rest'=>int,'replacement'=>int,'agent'=>Agent]
   */
  public function countWorkDaysForSite(int $siteId, $start, $end): array
  {
    Log::channel('planning')->info('count.start', ['site' => $siteId, 'start' => $start, 'end' => $end]);

    $start = $start instanceof Carbon ? $start->startOfDay() : Carbon::parse($start)->startOfDay();
    $end = $end instanceof Carbon ? $end->endOfDay() : Carbon::parse($end)->endOfDay();

    $entries = $this->repo->getPlanningAgentsBetween($siteId, $start, $end);

    $result = [];
    foreach ($entries as $entry) {
      $aid = $entry->agent_id;
      if (!isset($result[$aid])) {
        $result[$aid] = ['worked' => 0, 'absent' => 0, 'rest' => 0, 'replacement' => 0, 'agent' => $entry->agent];
      }

      $status = strtolower($entry->status);
      match ($status) {
        'present' => $result[$aid]['worked']++,
        'absent' => $result[$aid]['absent']++,
        'repos' => $result[$aid]['rest']++,
        'permutation' => $result[$aid]['replacement']++,
        default => $result[$aid]['worked']++,
      };

      // si un remplacant remplace, on peut enregistrer +1 pour lui aussi (optionnel)
      if ($entry->remplacant_id) {
        $rid = $entry->remplacant_id;
        if (!isset($result[$rid]))
          $result[$rid] = ['worked' => 0, 'absent' => 0, 'rest' => 0, 'replacement' => 0, 'agent' => $entry->remplacant];
        $result[$rid]['worked']++;
      }
    }

    Log::channel('planning')->info('count.end', ['site' => $siteId, 'count' => count($result)]);
    return $result;
  }

  /**
   * Compte pour un agent précis.
   */
  public function countWorkDaysForAgent(int $agentId, $start, $end): array
  {
    Log::channel('planning')->info('count.agent.start', ['agent' => $agentId, 'start' => $start, 'end' => $end]);
    $start = $start instanceof Carbon ? $start->startOfDay() : Carbon::parse($start)->startOfDay();
    $end = $end instanceof Carbon ? $end->endOfDay() : Carbon::parse($end)->endOfDay();

    $entries = $this->repo->getAgentPlanningEntriesBetween($agentId, $start, $end);

    $stats = ['worked' => 0, 'absent' => 0, 'rest' => 0, 'replacement' => 0, 'entries' => $entries];
    foreach ($entries as $entry) {
      $s = strtolower($entry->status);
      match ($s) {
        'present' => $stats['worked']++,
        'absent' => $stats['absent']++,
        'repos' => $stats['rest']++,
        'permutation' => $stats['replacement']++,
        default => $stats['worked']++,
      };
    }
    Log::channel('planning')->info('count.agent.end', ['agent' => $agentId, 'stats' => $stats]);
    return $stats;
  }

  /**
   * Exporte un planning en PDF (Blade -> Dompdf)
   */
  public function exportPdf(int $planningId)
  {
    Log::channel('planning')->info('export.start', ['planning' => $planningId]);
    $planning = $this->repo->find($planningId);
    if (!$planning) {
      Log::channel('planning')->error('export.notfound', ['planning' => $planningId]);
      throw new \Exception("Planning $planningId introuvable");
    }

    $pdf = Pdf::loadView('plannings.pdf', ['planning' => $planning]);
    Log::channel('planning')->info('export.success', ['planning' => $planningId]);
    return $pdf->download("planning_{$planning->site->id}_{$planning->date->toDateString()}.pdf");
  }
}
