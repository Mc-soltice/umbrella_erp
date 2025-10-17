<?php

namespace App\Http\Resources\Agents;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'matricule' => $this->matricule,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'location' => $this->location,
            'phone' => $this->phone,
            'email' => $this->email,
            'site' => [
                'id' => $this->site->id ?? null,
                'name' => $this->site->name ?? null,
                'location' => $this->site->location ?? null,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
