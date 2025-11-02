<?php

namespace App\Http\Controllers\Api\Plannings;

use App\Http\Requests\PlanningRequest;
use App\Http\Requests\Planning\PlanningCountRequest;
use App\Http\Resources\Plannings\PlanningResource;
use App\Services\Plannings\PlanningService;
use App\Models\Planning;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Controller REST pour les plannings.
 */
class PlanningController extends Controller
{
  public function __construct(protected PlanningService $service)
  {

  }

  public function index(Request $request)
  {
    $siteId = $request->query('site_id');
    $date = $request->query('date');
    $plannings = $this->service->getAll($siteId, $date);
    return PlanningResource::collection($plannings);
  }

  public function store(PlanningRequest $request)
  {
    $planning = $this->service->create($request->validated());
    return new PlanningResource($planning);
  }

  public function show(Planning $planning)
  {
    return new PlanningResource($planning->load('agents.agent', 'agents.remplacant', 'site', 'creator'));
  }

  public function update(PlanningRequest $request, Planning $planning)
  {
    $p = $this->service->update($planning, $request->validated());
    return new PlanningResource($p);
  }

  public function destroy(Planning $planning)
  {
    $this->service->delete($planning);
    return response()->json([], 204);
  }

  public function export(int $id)
  {
    return $this->service->exportPdf($id);
  }

  public function countForSite(int $siteId, PlanningCountRequest $request)
  {
    $dates = $request->dates();
    $res = $this->service->countWorkDaysForSite($siteId, $dates['start'], $dates['end']);
    return response()->json($res);
  }

  public function countForAgent(int $agentId, PlanningCountRequest $request)
  {
    $dates = $request->dates();
    $res = $this->service->countWorkDaysForAgent($agentId, $dates['start'], $dates['end']);
    return response()->json($res);
  }
}
