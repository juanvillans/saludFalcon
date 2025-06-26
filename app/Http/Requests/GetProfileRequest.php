<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetProfileRequest extends FormRequest
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
            'search' => 'nullable|string',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'status' => 'nullable|integer|exists:status_cases,id',
            'condition' => 'nullable|string',
            'area_id' => 'nullable|integer|exists:areas,id',
            'patient_ci' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'case_id' => 'nullable|integer',
        ];
    }
}
