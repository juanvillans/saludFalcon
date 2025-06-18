<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetPatientRequest extends FormRequest
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
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'sort_by' => 'sometimes|string|in:id,name,ci,created_at',
            'sort_direction' => 'sometimes|string|in:asc,desc',
            'search' => 'sometimes|string|max:100',
            'ci' => 'sometimes|string',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Establecer valores por defecto
        $this->merge([
            'page' => $this->page ?? 1,
            'per_page' => $this->per_page ?? 15,
            'sort_by' => $this->sort_by ?? 'id',
            'sort_direction' => $this->sort_direction ?? 'desc',
        ]);
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages()
    {
        return [
            'per_page.max' => 'No se pueden mostrar más de 100 registros por página',
            'sort_by.in' => 'El campo para ordenar no es válido',
            'sort_direction.in' => 'La dirección de ordenamiento debe ser "asc" o "desc"',
        ];
    }
}
