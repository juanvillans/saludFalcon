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

            'area_id' => $this->area_id,
            'area_name' => $this->area->name,

            'patient_condition_id' => $this->patient_condition_id,
            'patient_condition_name' => $this->condition->name,

            'status_id' => $this->status_id,
            'status_name' => $this->status->name,

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


        ];
    }
}
