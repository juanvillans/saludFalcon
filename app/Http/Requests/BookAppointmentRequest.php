<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookAppointmentRequest extends FormRequest
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
            'day_reserved' => 'required|string',
            'time_reserved' => 'required|string',
            'appointment_data' => 'required|array',
            'appointment_data.ci' => 'required|string|max:20',
            'appointment_data.name' => 'required|string|max:100',
            'appointment_data.last_name' => 'required|string|max:100',
            'appointment_data.sex' => 'required',
            'appointment_data.date_birth' => 'required|date_format:Y-m-d|before:today',
            'appointment_data.phone_number' => 'required|string|max:15',
            'appointment_data.email' => 'required|email|max:100',
        ];
    }
}
