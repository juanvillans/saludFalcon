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
            'current_status_name' => $this->statusCase->name,

            'municipality_id' => $this->patient->municipality_id,
            'municipality_name' => $this->patient->municipality->name,
            'parish_id' => $this->patient->parish_id,
            'parish_name' => $this->patient->parish->name,

            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_last_name' => $this->user->last_name,
            'user_ci' => $this->user->ci, 

            "patient_id" => $this->patient->id,
            "patient_name" => $this->patient->name,
            "patient_last_name" => $this->patient->last_name,
            "patient_ci" => $this->patient->ci,
            "patient_phone_number" => $this->patient->phone_number,
            "patient_sex" => $this->patient->sex,
            "patient_date_birth" => $this->patient->date_birth,
            "patient_address" => $this->patient->address,

            
            'area_id' => $this->area_id,
            'area_name' => $this->area->name,
            
            'evolutions' => new EvolutionCollection($this->whenLoaded('evolutions')),
        ];

    }
}
