<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
                'patient_id' => $this->patient->id,
                'patient_ci' => $this->patient->ci,
                'patient_name' => $this->patient->name,
                'patient_last_name' => $this->patient->last_name,
                'patient_phone_number' => $this->patient->phone_number,
                'patient_sex' => $this->patient->sex,
                'patient_date_birth' => $this->patient->date_birth,
                'current_status' => $this->current_status,
                'cases' => $this->cases,
        ];
    }
}
