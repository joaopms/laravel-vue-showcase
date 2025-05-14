<?php

namespace App\Http\Requests;

use App\Helpers\AgeParser;
use App\TimeOfDay;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentSchedule extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client.name' => ['required', 'string', 'min:2', 'max:50'],
            'client.email' => ['required', 'email'],
            'animal.name' => ['required', 'string', 'min:2', 'max:50'],
            'animal.type' => ['required', 'string', 'min:3', 'max:20'],
            'animal.age_years' => ['required', 'numeric', 'integer', 'max:100'],
            'animal.age_months' => ['required', 'numeric', 'integer', 'max:12'],
            'appointment.preferred_date' => ['required', Rule::date()->todayOrAfter()],
            'appointment.preferred_time' => ['required', 'array', 'min:1', 'max:2'],
            'appointment.preferred_time.*' => ['required', Rule::enum(TimeOfDay::class)->only(TimeOfDay::selectable()), 'distinct'],
            'appointment.symptoms' => ['required', 'string', 'min:10', 'max:255'],
        ];
    }

    public function getAnimalData(): array
    {
        return $this->collect('animal')
            ->except(['age_years', 'age_months'])
            ->toArray();
    }

    public function getAnimalAgeInMonths(): int
    {
        return AgeParser::from(
            $this['animal.age_years'],
            $this['animal.age_months']
        )->asMonths();
    }

    /**
     * Casts preferred times from string to TimeOfDay enum
     *
     * This is necessary because they are sent as strings from the frontend
     */
    public function getAppointmentTimes(): TimeOfDay
    {
        return TimeOfDay::fromInputData($this['appointment.preferred_time']);
    }

    public function getAppointmentData(): array
    {
        return [
            ...$this['appointment'],
            'preferred_time' => $this->getAppointmentTimes(),
            'animal_age_months' => $this->getAnimalAgeInMonths(),
        ];
    }
}
