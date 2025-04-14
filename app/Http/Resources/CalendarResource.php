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
            'status_name' => $this->status ? 'Disponible' : 'No disponible',
            'duration_per_appointment' => $this->duration_per_appointment,
            'duration_options' => $this->duration_options,
            'availability' => $this->availability,
            'adjusted_availability' => $this->adjusted_availability,
            'booked_appointment_settings' => $this->booked_appointment_settings,
            'programming_slot' => $this->programming_slot,
            'fields' => $this->fields,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_last_name' => $this->user->last_name,
            'user_photo' => $this->user->photo,
            'user_specialty_id' => $this->user->specialty->id,
            'user_specialty_name' => $this->user->specialty->name,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
