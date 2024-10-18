<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateServiceRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
        
            "doctor_id" => ['required'],
            "specialty_id" => ['required'],
            "title" => ['required'],
            "duration_per_appointment" => ['required'],
            'duration_options' => ['required'],
            "availability" => ['required'],
            "adjusted_availability" => ['sometimes'],
            "programming_slot" => ['required'],
            "booked_appointment_settings" => ['required'],
            "description" => ['required'],
            "fields" => ['required'],


        ];
    }
}
