<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'patient_id' => $this->id,
            'patient_ci' => $this->ci,
            'patient_name' => $this->name,
            'patient_last_name' => $this->last_name,
            'patient_phone_number' => $this->phone_number,
            'patient_sex' => $this->sex,
            'patient_date_birth' => $this->date_birth,
        ];
    }
}
