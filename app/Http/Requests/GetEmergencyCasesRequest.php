<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class GetEmergencyCasesRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'search' => 'nullable|string',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'order_by' => ['nullable','string', Rule::in(['id', 'updated_at'])],
            'status' => 'nullable|integer|exists:status_cases,id',
            'condition' => 'nullable|string',
            'area_id' => 'nullable|integer|exists:areas,id',
            'ci' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'case_id' => 'nullable|integer',
            'specialty_id' => 'nullable|integer|exists:specialties,id',
            'age' => 'nullable|integer|min:0|max:120',

        ];
    }

    /**
     * Mensajes de validación personalizados
     */
    public function messages(): array
    {
        return [
            'page.integer' => 'El número de página debe ser un valor entero',
            'page.min' => 'El número de página no puede ser menor a 1',

            'order_by.in' => 'El campo ordenar por solo puede ser: id o updated_at',

            'per_page.integer' => 'El número de items por página debe ser un entero',
            'per_page.min' => 'El mínimo de items por página es 1',
            'per_page.max' => 'El máximo de items por página es 100',

            'status.integer' => 'El estado debe ser un ID válido',
            'status.exists' => 'El estado seleccionado no existe en nuestros registros',

            'area_id.integer' => 'El área debe ser un ID válido',
            'area_id.exists' => 'El área seleccionada no existe en nuestros registros',

            'start_date.date' => 'La fecha inicial debe tener un formato válido',

            'end_date.date' => 'La fecha final debe tener un formato válido',
            'end_date.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha inicial',

            'case_id.integer' => 'El ID de caso debe ser un número entero',

            'specialty_id.integer' => 'La especialidad debe ser un ID válido',
            'specialty_id.exists' => 'La especialidad seleccionada no existe en nuestros registros',

            'age.integer' => 'La edad debe ser un número entero',
            'age.min' => 'La edad no puede ser negativa',
            'age.max' => 'La edad no puede ser mayor a 120 años',
        ];
    }

    /**
     * Atributos personalizados para los mensajes
     */
    public function attributes(): array
    {
        return [
            'ci' => 'cédula de identidad',
            'per_page' => 'items por página',
            'area_id' => 'área',
            'specialty_id' => 'especialidad',
        ];
    }

    /**
     * Get the validated data with proper parameter names.
     */
    public function validatedParams(): array
    {
        return [
            'search' => $this->input('search'),
            'page' => $this->input('page'),
            'per_page' => $this->input('per_page'),
            'status' => $this->input('status'),
            'condition' => $this->input('condition'),
            'area_id' => $this->input('area_id'),
            'patient_ci' => $this->input('ci'),
            'start_date' => $this->input('start_date'),
            'end_date' => $this->input('end_date'),
            'case_id' => $this->input('case_id'),
            'specialty_id' => $this->input('specialty_id'),
            'age' => $this->input('age'),
        ];
    }
}
