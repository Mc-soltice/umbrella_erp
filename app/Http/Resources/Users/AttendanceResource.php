<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource JSON pour le pointage
 */
class AttendanceResource extends JsonResource
{
    /**
     * Transforme les données en JSON
     *
     * @param \Illuminate\Http\Request $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'user' => [
                'matricule' => $this->user->matricule,
                'name' => $this->user->first_name . ' ' . $this->user->last_name,
            ],
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'worked_hours' => $this->worked_hours,
        ];
    }
}
