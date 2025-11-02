<?php

namespace App\Repositories\Plannings;

use App\Models\Planning;
use App\Models\PlanningAgent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Models\Attendance;

/**
 * Repository pour les opérations DB liées aux plannings (site/date + agents).
 *
 * Toutes les interactions qui modifient la BD passent par ici.
 */
class PlanningRepository
{
  /**
   * Crée un planning + ses entrées agents (transactionnel).
   *
   * @param array $data [site_id, date, created_by, agents[]]
   * @return Planning
   */
  public function createWithAgents(array $data): Planning
  {
    Log::channel('planning')->info('planning.create.start', [
      'site_id' => $data['site_id'],
      'date' => $data['date'],
      'by' => $data['created_by']
    ]);

    $planning = Planning::create([
      'site_id' => $data['site_id'],
      'date' => Carbon::parse($data['date'])->toDateString(),
      'created_by' => $data['created_by'],
    ]);

    foreach ($data['shifts'] as $shift => $shiftData) {
      foreach ($shiftData['agents'] as $agent) {
        $planning->agents()->create([
          'agent_id' => $agent['agent_id'],
          'shift' => $shift,
          'status' => $agent['status'],
          'motif' => $agent['motif'] ?? null,
          'remplacant_id' => $agent['remplacant_id'] ?? null,
        ]);
      }
    }


    Log::channel('planning')->info('planning.create.success', ['planning_id' => $planning->id]);

    return $planning->load('agents.agent', 'agents.remplacant', 'site', 'creator');
  }


  /**
   * Met à jour un planning et remplace les entrées agents (transactionnel).
   *
   * @param Planning $planning
   * @param array $data
   * @return Planning
   */
  public function updateWithAgents(Planning $planning, array $data): Planning
  {
    Log::channel('planning')->info('planning.update.start', ['planning_id' => $planning->id]);

    $planning->update(['site_id' => $data['site_id'], 'date' => Carbon::parse($data['date'])->toDateString()]);

    // supprimer anciennes entrées
    $planning->agents()->delete();

    foreach ($data['agents'] as $agent) {
      $planning->agents()->create([
        'agent_id' => $agent['agent_id'],
        'shift' => $agent['shift'],
        'status' => $agent['status'],
        'motif' => $agent['motif'] ?? null,
        'remplacant_id' => $agent['remplacant_id'] ?? null,
      ]);
    }

    Log::channel('planning')->info('planning.update.success', ['planning_id' => $planning->id]);
    return $planning->load('agents.agent', 'agents.remplacant', 'site', 'creator');
  }

  /**
   * Supprime un planning (soft delete si tu as softDeletes)
   *
   * @param Planning $planning
   * @return bool
   */
  public function delete(Planning $planning): bool
  {
    Log::warning('planning.delete.start', ['planning_id' => $planning->id]);
    $res = $planning->delete();
    Log::warning('planning.delete.end', ['planning_id' => $planning->id, 'deleted' => $res]);
    return $res;
  }

  /**
   * Récupère les plannings filtrés par site/date
   *
   * @param int|null $siteId
   * @param string|null $date (YYYY-MM-DD)
   * @return Collection|Planning[]
   */
  public function getAll(?int $siteId = null, ?string $date = null): Collection
  {
    $query = Planning::with('agents.agent', 'site', 'creator');

    if ($siteId) {
      $query->where('site_id', $siteId);
    }
    if ($date) {
      $query->whereDate('date', $date);
    }

    return $query->get();
  }

  /**
   * Récupère un planning par id
   *
   * @param int $id
   * @return Planning|null
   */
  public function find(int $id): ?Planning
  {
    return Planning::with('agents.agent', 'site', 'creator')->find($id);
  }

  /**
   * Récupère planning_agents filtered by planning ids (used for attendance creation/count)
   */
  public function getPlanningAgentsBetween(int $siteId, Carbon|string $start, Carbon|string $end): Collection
  {
    $start = $start instanceof Carbon ? $start->toDateString() : $start;
    $end = $end instanceof Carbon ? $end->toDateString() : $end;

    return PlanningAgent::with('agent', 'remplacant', 'planning')
      ->whereHas('planning', function ($q) use ($siteId, $start, $end) {
        $q->where('site_id', $siteId)
          ->whereBetween('date', [$start, $end]);
      })->get();
  }

  public function getAgentPlanningEntriesBetween(int $agentId, Carbon|string $start, Carbon|string $end): Collection
  {
    $start = $start instanceof Carbon ? $start->toDateString() : $start;
    $end = $end instanceof Carbon ? $end->toDateString() : $end;

    return PlanningAgent::with('planning', 'remplacant')
      ->where('agent_id', $agentId)
      ->whereHas('planning', function ($q) use ($start, $end) {
        $q->whereBetween('date', [$start, $end]);
      })->get();
  }

  /**
   * Create/Update attendance rows based on planning entries.
   * Returns array of created attendances.
   */
  public function createAttendancesFromPlanning(Planning $planning): array
  {
    $created = [];
    foreach ($planning->agents as $entry) {
      $statusEnum = \App\Enums\AttendanceStatus::fromPlanningStatus($entry->status);
      $attendance = Attendance::updateOrCreate(
        ['agent_id' => $entry->agent_id, 'date' => $planning->date->toDateString()],
        [
          'planning_agent_id' => $entry->id,
          'status' => $statusEnum->value,
          'reason' => $entry->motif ?? null,
        ]
      );
      $created[] = $attendance;
    }
    return $created;
  }
}