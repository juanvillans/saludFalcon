<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EvolutionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cambiar a false y manejar autorización según sea necesario
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'emergency_case_id' => [
                'required',
                'integer',
                'exists:emergency_cases,id'
            ],
            'user_id' => [
                'required',
                'integer',
                'exists:users,id'
            ],
            'area_id' => [
                'required',
                'integer',
                'exists:areas,id'
            ],
            'patient_condition_id' => [
                'required',
                'integer',
                'exists:patient_conditions,id'
            ],
            'status_id' => [
                'required',
                'integer',
                'exists:status_cases,id'
            ],
            'evolution' => [
                'required',
                'string',
                'min:1'
            ],
            'diagnosis' => [
                'nullable',
                'string',
                'min:1'
            ],
            'treatment' => [
                'nullable',
                'string',
                'min:1'
            ],
            'destiny' => [
                'nullable',
                'string',
                'max:255'
            ],
            'is_interconsult' => [
                'boolean'
            ],
            'departure_date' => [
                'nullable',
                'date'
            ],
            'departure_hour' => [
                'nullable',
                'date_format:H:i'
            ],
            'bed_number' => [
                'nullable',
                'integer',
                'min:1'
            ]
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->user()->id,
        ]);
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'emergency_case_id.required' => 'El caso de emergencia es requerido.',
            'emergency_case_id.exists' => 'El caso de emergencia seleccionado no existe.',
            'user_id.required' => 'El usuario es requerido.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
            'area_id.required' => 'El área es requerida.',
            'area_id.exists' => 'El área seleccionada no existe.',
            'patient_condition_id.required' => 'La condición del paciente es requerida.',
            'patient_condition_id.exists' => 'La condición del paciente seleccionada no existe.',
            'status_id.required' => 'El estado del caso es requerido.',
            'status_id.exists' => 'El estado del caso seleccionado no existe.',
            'evolution.required' => 'La evolución es requerida.',
            'evolution.min' => 'La evolución debe tener al menos :min caracteres.',
            'departure_hour.date_format' => 'La hora de salida debe estar en formato HH:MM.',
            'bed_number.min' => 'El número de cama debe ser al menos :min.'
        ];
    }
}
