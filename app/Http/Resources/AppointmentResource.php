<?php

namespace App\Http\Resources;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Appointment */
class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'preferred_date' => $this->preferred_date_formatted,
            'preferred_time' => $this->preferred_time_formatted,
            'animal' => [
                'name' => $this->animal->name,
                'type' => $this->animal->type,
            ],
            'client' => [
                'name' => $this->animal->client->name,
            ],
            'medic' => $this->whenLoaded('medic', fn (User $medic) => [
                'name' => $medic->name,
            ]),
        ];
    }
}
