<?php

namespace App\Http\Requests;

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
            'animal.ageYears' => ['required', 'numeric', 'integer', 'max:100'],
            'animal.ageMonths' => ['required', 'numeric', 'integer', 'max:12'],
            'appointment.preferredDate' => ['required', Rule::date()->todayOrAfter()],
            'appointment.preferredTime' => ['required', 'array', 'min:1', 'max:2'],
            'appointment.preferredTime.*' => ['required', Rule::enum(TimeOfDay::class)->only(TimeOfDay::selectable())],
            'appointment.symptoms' => ['required', 'string', 'min:10', 'max:255'],
        ];
    }
}
