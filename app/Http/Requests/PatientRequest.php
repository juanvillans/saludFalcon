<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
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
        $patientID = $this->input('patient_id') ?? 0;

        return [
            'patient_ci' => ['required','unique:patients,ci,'. $patientID, 'string', 'min:6', 'max:30'],
            'patient_name' => ['required','string', 'max:50', 'min:3'],
            'patient_last_name' => ['required', 'string', 'max:50', 'min:3'],
            'patient_phone_number' => 'nullable|string|max:30',
            'patient_sex' => 'nullable|string|in:Masculino,Femenino',
            'patient_date_birth' => ['required'],
            'patient_date_birth' => 'nullable|date|before_or_equal:today',
            'patient_address' => 'nullable|string|max:255',
            'municipality_id' => 'nullable',
            'parish_id' => 'nullable',
        ];
    }
}
