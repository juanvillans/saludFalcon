<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
        $userId = $this->route('usuario'); 

        return [
            
            'ci'=> ['required','max:15','string',Rule::unique(User::class)->ignore($userId)],
            "name" => ['required','max:40','string'],
            "last_name" => ['required','max:40','string'],
            'email'=> ['required','email',Rule::unique(User::class)->ignore($userId)],
            "phone_number" => ['required','max:14','string'],
            "role_name" => ['required'],
            "specialties" => ['sometimes'],
        ];
    }
}
