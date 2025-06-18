<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreatePatientRequest extends FormRequest
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
            'ci' => 'required|string|max:30|unique:patients,ci',
            'name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'phone_number' => 'nullable|string|max:30',
            'sex' => ['required', 'string', Rule::in(['Masculino', 'Femenino', 'other'])],
            'date_birth' => 'required|date|before_or_equal:today',
            'municipality_id' => 'nullable|integer|exists:municipalities,id',
            'parish_id' => 'nullable|integer|exists:parishes,id',
            'address' => 'nullable|string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'ci' => 'cédula de identidad',
            'name' => 'nombre',
            'last_name' => 'apellido',
            'phone_number' => 'teléfono',
            'sex' => 'sexo',
            'date_birth' => 'fecha de nacimiento',
            'municipality_id' => 'municipio',
            'parish_id' => 'parroquia',
        ];
    }

     public function messages(): array
    {
        return [
            'ci.unique' => 'La cédula de identidad ya está registrada',
            'date_birth.before_or_equal' => 'La fecha de nacimiento no puede ser futura',
            'sex.in' => 'El sexo debe ser masculino, femenino u otro',
            'municipality_id.exists' => 'El municipio seleccionado no existe',
            'parish_id.exists' => 'La parroquia seleccionada no existe',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'ci' => preg_replace('/[^0-9]/', '', trim($this->ci)),
            'name' => trim($this->name),
            'last_name' => trim($this->last_name),
            'phone_number' => $this->phone_number ? preg_replace('/[^0-9]/', '', $this->phone_number) : null,
        ]);
    }
}
