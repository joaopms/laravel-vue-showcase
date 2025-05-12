<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListAppointmentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Filters
            'start' => ['date'], // Date
            'end' => ['date'],   // Date
        ];
    }
}
