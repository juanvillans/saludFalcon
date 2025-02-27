<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestUserRequest extends FormRequest
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

            'ci' => ['required','unique:users,ci'],
            'name' => ['required'],
            'last_name' => ['required'],
            "email" => ['required','email','unique:users,email'],
            "medical_license" => ['required', 'max:50', 'string', 'unique:users,medical_license'],
            'phone_number' => ['required'],
            'specialty_id' => ['required'],
        
        ];
    }
}
