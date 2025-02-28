<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvolutionRequest extends FormRequest
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

            "diagnosis" => ['required'],
            "treatment" => ['required'],
            "status" => ['required'],
            "area_id" => ['sometimes'],
            "destiny" => ['sometimes'],
            "departure_date" => ['sometimes'],
            "departure_hour" => ['sometimes'],
        ];
    }
}
