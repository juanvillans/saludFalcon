<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaseRequest extends FormRequest
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
            'patient_id' => ['sometimes'],
            'current_patient_condition_id' => ['required'],
            'user_id' => ['required'],
            'area_id' => ['required'],
            'entry_date' => ['required'],
            'entry_hour' => ['required'],
            'current_status' => ['required'],
            'departure_date' => ['sometimes'],
            'departure_hour' => ['sometimes'],
            'reason' => ['required'],
            'diagnosis' => ['required'],
            'treatment' => ['required'],
            'destiny' => ['sometimes'],
            'patient_sex' => ['required'],
            'patient_ci' => ['required','min:6'],

        ];
    }
}
