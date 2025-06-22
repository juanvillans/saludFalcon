<?php

namespace App\Http\Requests;

use App\Enums\StatusCaseEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateEmergencyCaseRequest extends FormRequest
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
            'patient_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:patients,id'
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
            'current_patient_condition_id' => [
                'nullable',
                'integer',
                'exists:patient_conditions,id'
            ],
            'entry_date' => [
                'required',
                'date',
                'before_or_equal:today'
            ],
            'entry_hour' => [
                'required',
                'string',
                'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/' // Formato HH:MM
            ],
            'current_status_case' => [
                'sometimes',
                'integer',
                'exists:status_cases,id'
            ],
            'destiny' => [
                'nullable',
                'string',
                'max:255'
            ],
            'departure_date' => [
                'nullable',
                'date',
                'after_or_equal:entry_date',
                'required_with:departure_hour'
            ],
            'departure_hour' => [
                'nullable',
                'string',
                'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/',
                'required_with:departure_date'
            ],
            'reason' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'diagnosis' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'treatment' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'bed_number' => [
                'required',
                'integer',
                'min:1'
            ],
            'last_message' => [
                'sometimes',
                'nullable',
                'string',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'El paciente es requerido',
            'patient_id.exists' => 'El paciente seleccionado no existe',
            'user_id.required' => 'El médico responsable es requerido',
            'user_id.exists' => 'El usuario seleccionado no existe',
            'area_id.required' => 'El área es requerida',
            'area_id.exists' => 'El área seleccionada no existe',
            'entry_date.required' => 'La fecha de ingreso es requerida',
            'entry_date.before_or_equal' => 'La fecha de ingreso no puede ser futura',
            'entry_hour.required' => 'La hora de ingreso es requerida',
            'entry_hour.regex' => 'La hora debe tener formato HH:MM',
            'current_status_case.in' => 'El estado seleccionado no es válido',
            'departure_date.after_or_equal' => 'La fecha de egreso no puede ser anterior a la de ingreso',
            'departure_hour.regex' => 'La hora de egreso debe tener formato HH:MM',
            'bed_number.required' => 'El número de cama es requerido',
            'bed_number.min' => 'El número de cama debe ser al menos 1',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'current_status_case' => $this->input('current_status_case', StatusCaseEnum::INGRESADO->value)
        ]);
    }
}
