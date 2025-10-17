<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'matricule'  => $this->matricule,
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'location'   => $this->location,
            'is_locked'  => (bool) $this->is_locked,
            'roles' => $this->getRoleNames(), // Méthode spécifique de Spatie
            'permissions' => $this->getAllPermissions()->pluck('name'), // Toutes les permissions (directes + via les rôles)
        ];
    }
}