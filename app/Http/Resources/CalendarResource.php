<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'status' => (bool)$this->status,
            'status_name' => $this->status ? 'Available' : 'Not Available',
            'duration_per_appointment' => $this->duration_per_appointment,
            'duration_options' => $this->duration_options,
            'availability' => $this->availability,
            'adjusted_availability' => $this->adjusted_availability,
            'booked_appointment_settings' => $this->booked_appointment_settings,
            'fields' => $this->fields,
            'user' => [
                "id" => $this->user->id,
                "ci" => $this->user->ci,
                "name" => $this->user->name,
                "last_name" => $this->user->last_name,
                "email" => $this->user->email,
                "phone_number" => $this->user->phone_number,
                "created_at" => $this->user->created_at,
                "specialty" => $this->user->specialty,
                "specialty_id" => $this->user->specialty->id,
                "role_id" => $this->user->roles[0]->id,
                "role_name" => $this->user->roles[0]->name,
                "medical_license" => $this->user->medical_license,
                "photo" => $this->user->photo,
                "status" => $this->user->status,
            ],
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
