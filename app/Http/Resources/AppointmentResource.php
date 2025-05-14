<?php

namespace App\Http\Resources;

use App\Models\Appointment;
use App\ResourceConditions;
use App\TimeOfDay;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Appointment
 * @property bool $listing
 * @property bool $showing
 */
class AppointmentResource extends JsonResource
{
    use ResourceConditions;

    protected array $conditions = ['listing', 'showing'];

    public function toArray(Request $request): array
    {
        return [
            'appointment' => [
                'id' => $this->when($this->listing, $this->id),
                'preferred_date_formatted' => $this->when($this->listing, $this->preferred_date_formatted),
                'preferred_time_formatted' => $this->when($this->listing, $this->preferred_time_formatted),
                'preferred_date' => $this->when($this->showing, $this->preferred_date->toDateString()),
                'preferred_time' => $this->when($this->showing, TimeOfDay::toInputData($this->preferred_time)),
                'symptoms' => $this->symptoms,
            ],
            'animal' => [
                'name' => $this->animal->name,
                'type' => $this->animal->type,
                'age_human' => $this->when($this->listing, $this->animal_age->human()),
                'age_years' => $this->when($this->showing, $this->animal_age->years()),
                'age_months' => $this->when($this->showing, $this->animal_age->months()),
            ],
            'client' => [
                'name' => $this->animal->client->name,
                'email' => $this->when($this->showing, $this->animal->client->email),
            ],
            'medic' => [
                'id' => $this->when($this->showing, $this->medic?->id),
                'name' => $this->when($this->listing, $this->medic?->name),
            ],

            // User permissions
            '_can' => [
                'update' => $request->user()->can('update', $this->resource),
                'delete' => $request->user()->can('delete', $this->resource),
                'assign' => $request->user()->can('assign', $this->resource),
            ],
        ];
    }
}
