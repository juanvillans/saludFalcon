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
            'patient_ci' => ['required','unique:patients,ci,'. $patientID],
            'patient_name' => ['required'],
            'patient_last_name' => ['required'],
            'patient_phone_number' => ['required'],
            'patient_sex' => ['required'],
            'patient_date_birth' => ['required'],
        ];
    }
}
