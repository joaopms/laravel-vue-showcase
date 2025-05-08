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
            'client.name' => ['required', 'string', 'between:2,50'],
            'client.email' => ['required', 'email'],
            'animal.name' => ['required', 'string', 'between:2,50'],
            'animal.type' => ['required', 'string', 'between:3,20'],
            'animal.ageMonths' => ['required', 'numeric', 'integer', 'max:1200'],
            'appointment.preferredDate' => ['required', Rule::date()->todayOrAfter()],
            'appointment.preferredTime' => ['required', 'array', 'between:1,2'],
            'appointment.preferredTime.*' => ['required', Rule::enum(TimeOfDay::class)->only(TimeOfDay::selectable())],
            'appointment.symptoms' => ['required', 'string', 'between:10,255'],
        ];
    }
}
