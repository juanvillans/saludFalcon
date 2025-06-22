<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMessageRequest extends FormRequest
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
            'emergency_case_id' => [
                'required',
                'integer',
                'exists:emergency_cases,id'
            ],
            'body' => [
                'required',
                'string',
                'min:1',
                'max:5000' // Ajusta según tus necesidades
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'emergency_case_id.required' => 'El ID del caso de emergencia es requerido',
            'emergency_case_id.exists' => 'El caso de emergencia seleccionado no existe',
            'body.required' => 'El contenido del mensaje es requerido',
            'body.min' => 'El mensaje debe tener al menos 1 carácter',
            'body.max' => 'El mensaje no puede exceder los 5000 caracteres'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'emergency_case_id' => 'caso de emergencia',
            'body' => 'contenido del mensaje'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge(['user_id' => auth()->id()]);
    }
}
