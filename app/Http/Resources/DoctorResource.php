<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->id,
            'user_ci' => $this->ci,
            'user_name' => $this->name,
            'user_last_name' => $this->last_name,
            'user_email' => $this->email,
            'user_phone_number' => $this->phone_number,
            'user_specialty_id' => $this->specialty_id,
            'user_specialty_name' => $this->specialty->name,
        ];
    }
}
