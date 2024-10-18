<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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

            "ci" => ['required','max:15','string','unique:users,ci'],
            "name" => ['required','max:40','string'],
            "last_name" => ['required','max:40','string'],
            "email" => ['required','email','unique:users,email'],
            "phone_number" => ['required','max:14','string'],
            "role_name" => ['required'],
            "specialties" => ['sometimes'],
            "specialties_ids" => ['sometimes'],



        ];
    }
}
