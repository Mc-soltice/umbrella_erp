<?php
namespace App\Http\Resources\Plannings;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Plannings\PlanningAgentResource;

class PlanningResource extends JsonResource
{
    public function toArray($request): array
    {
        // regroupe par shift et renvoie arrays
        $shifts = $this->agents->groupBy('shift')->map(function ($g) {
            return PlanningAgentResource::collection($g);
        });

        return [
            'id' => $this->id,
            'site' => $this->site?->name,
            'date' => $this->date->toDateString(),
            'shifts' => [
                'day' => $shifts['morning'] ?? [],
                'evening' => $shifts['evening'] ?? [],
            ],
            'created_by' => $this->creator?->first_name . ' ' . $this->creator?->last_name,
        ];
    }
}
