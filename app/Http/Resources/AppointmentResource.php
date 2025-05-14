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
            'id' => $this->id,
            'preferred_date' => $this->preferred_date_formatted,
            'preferred_time' => $this->preferred_time_formatted,
            'symptoms' => $this->symptoms,
            'animal' => [
                'name' => $this->animal->name,
                'type' => $this->animal->type,
                'age' => $this->animal_age_formatted,
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
