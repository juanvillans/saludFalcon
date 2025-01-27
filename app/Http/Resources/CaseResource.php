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
            'entry_date' => $this->entry_date,
            'entry_hour' => $this->entry_hour,
            'departure_date' => $this->departure_date,
            'departure_hour' => $this->departure_hour,
            'reason' => $this->reason,
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'current_status' => $this->current_status,

            'municipality_id' => $this->municipality_id,
            'municipality_name' => $this->municipality_name,
            'parish_id' => $this->parish_id,
            'parish_name' => $this->parish_name,

            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'user_last_name' => $this->user_last_name,
            'user_ci' => $this->user_ci, 

            "patient_id" => $this->patient_id,
            "patient_name" => $this->patient_name,
            "patient_last_name" => $this->patient_last_name,
            "patient_ci" => $this->patient_ci,
            "patient_phone_number" => $this->patient_phone_number,
            "patient_sex" => $this->patient_sex,
            "patient_date_birth" => $this->patient_date_birth,
            
            'area_id' => $this->area_id,
            'area_name' => $this->area_id,
            
            'evolutions' => new EvolutionCollection($this->whenLoaded('evolutions')),
        ];

    }
}
