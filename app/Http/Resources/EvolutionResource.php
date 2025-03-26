<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvolutionResource extends JsonResource
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
            'emergency_case_id' => $this->emergency_case_id,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'user_last_name' => $this->user->last_name,
            'user_specialty_id' => $this->user->specialty->id,
            'user_specialty_name' => $this->user->specialty->name,

            "patient_id" => $this->emergencyCase->patient->id,
            "patient_name" => $this->emergencyCase->patient->name,
            "patient_last_name" => $this->emergencyCase->patient->last_name,
            "patient_ci" => $this->emergencyCase->patient->ci,

            'area_id' => $this->area_id,
            'area_name' => $this->area->name,

            'patient_condition_id' => $this->patient_condition_id,
            'patient_condition_name' => $this->condition->name,

            'status_id' => $this->status_id,
            'status_name' => $this->status->name,

            'evolution' => $this->evolution,
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'destiny' => $this->destiny ?? null,
            'is_interconsult' => $this->is_interconsult,
            'created_at' => $this->created_at,
            'formatted_created_at' => $this->formatted_created_at,
            'updated_at' => $this->updated_at,
            'departure_date' => $this->departure_date,
            'formatted_departure_date' => $this->formatted_departure_date,
            'departure_hour' => $this->departure_hour,
            'reason' => $this->whenLoaded('emergencyCase', fn() => $this->emergencyCase->reason),
            'entry_date' => $this->whenLoaded('emergencyCase', fn() => $this->emergencyCase->entry_date),
            'formatted_entry_date' => $this->whenLoaded('emergencyCase', fn() => $this->emergencyCase->formatted_entry_date),
            'entry_hour' => $this->whenLoaded('emergencyCase', fn() => $this->emergencyCase->entry_hour),

        ];
    }
}
