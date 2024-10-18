<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        return [
            'id' => $this->id,
            'doctor_id' => $this->user_id,
            'doctor_name' => $this->user->name,
            'doctor_last_name' => $this->user->last_name,
            'doctor_ci' => $this->user->ci,
            'specialty_id' => $this->specialty_id,
            'specialty_name' => $this->specialty->name,
            'title' => $this->title,
            'duration_per_appointment' => $this->duration_per_appointment,
            'duration_options' => $this->duration_options,
            'availability' => $this->availability,
            'adjusted_availability' => $this->adjusted_availability,
            'programming_slot' => $this->programming_slot,
            'booked_appointment_settings' => $this->booked_appointment_settings,
            'description' => $this->description,
            'fields' => $this->fields,

        ];
    }
}
