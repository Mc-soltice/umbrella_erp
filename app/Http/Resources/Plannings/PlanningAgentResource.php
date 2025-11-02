<?php
namespace App\Http\Resources\Plannings;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanningAgentResource extends JsonResource
{
  public function toArray($request): array
  {
    return [
      'id' => $this->id,
      'agent_id' => $this->agent_id,
      'agent' => $this->agent ? [
        'id' => $this->agent->id,
        'matricule' => $this->agent->matricule,
        'first_name' => $this->agent->first_name,
        'last_name' => $this->agent->last_name,
      ] : null,
      'shift' => $this->shift,
      'status' => $this->status,
      'motif' => $this->motif,
      'remplacant' => $this->remplacant ? [
        'id' => $this->remplacant->id,
        'matricule' => $this->remplacant->matricule,
        'first_name' => $this->remplacant->first_name,
        'last_name' => $this->remplacant->last_name,
      ] : null,
    ];
  }
}
