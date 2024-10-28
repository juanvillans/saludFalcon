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
            "id" => $this->id,
            "cases" => $this->cases,
            "current_status" => $this->current_status,
            "patient_id" => $this->patient_id,
            "patient_name" => $this->patient_name,
            "patient_last_name" => $this->patient_last_name,
            "patient_ci" => $this->patient_ci,
            "patient_phone_number" => $this->patient_phone_number,
            "patient_sex" => $this->patient_sex,
            "patient_date_birth" => $this->patient_date_birth,
        ];

    }
}
