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
        // return [
        //     'patient_id',
        //     'current_patient_condition_id',
        //     'user_id',
        //     'area_id',
        //     'entry_date',
        //     'entry_hour',
        //     'current_status',
        //     'departure_date',
        //     'departure_hour',
        //     'reason',
        //     'diagnosis',
        //     'treatment',
        //     'destiny',
        // ];
    }
}
