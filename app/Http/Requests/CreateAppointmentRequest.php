<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAppointmentRequest extends FormRequest
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
            'service_id' => ['required'],
            'name' => ['required'],
            'last_name' => ['required'],
            'ci' => ['required'],
            'phone' => ['required'],
            'email' => ['required'],
            'other_fields' => ['required'],
            'start' => ['required'],
            'end' => ['required'],
            'date' => ['required'],
        ];
    }
}
