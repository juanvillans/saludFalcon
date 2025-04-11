<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalendarRequest extends FormRequest
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

            'user_id' => 'required|integer|exists:users,id',
            'specialty_id' => 'required|integer|exists:specialties,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'boolean',
            'duration_per_appointment' => 'required|integer|min:1',
            'availability' => 'required|array',

            'adjusted_availability' => 'required|array',

            'duration_options' => 'required|array',

            'booked_appointment_settings' => 'required|array',
            'booked_appointment_settings.allow_max_appointment_per_day' => 'required|boolean',
            'booked_appointment_settings.max_appointment_per_day' => 'required|integer',
            'booked_appointment_settings.time_between_appointment' => 'nullable|integer',
            
            'programming_slot' => 'required|array',
            'programming_slot.allow_max_reservation_time_before_appointment' => 'required|boolean',
            'programming_slot.allow_min_reservation_time_before_appointment' => 'required|boolean',
            'programming_slot.available_now_check' => 'required|boolean',
            'programming_slot.max_reservation_time_before_appointment' => 'required|integer|min:1',
            'programming_slot.min_reservation_time_before_appointment' => 'required|integer|min:1',
            
            'programming_slot.interval_date' => 'required|array',
            'programming_slot.interval_date.start_now_check' => 'required|boolean',
            'programming_slot.interval_date.end_never_check' => 'required|boolean',
            'programming_slot.interval_date.custom_end_date' => 'required|string',
            'programming_slot.interval_date.custom_start_date' => 'required|string',

        ];
    }
}
