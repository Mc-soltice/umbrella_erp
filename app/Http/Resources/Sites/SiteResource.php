<?php

namespace App\Http\Resources\Sites;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'responsable' => [
                'id' => $this->responsable->id ?? null,
                'first_name' => $this->responsable->first_name ?? null,
                'last_name' => $this->responsable->last_name ?? null,
                'email' => $this->responsable->email ?? null
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
