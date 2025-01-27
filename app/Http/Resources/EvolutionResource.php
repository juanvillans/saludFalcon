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
            'id' => $this->evolution_id,
            'emergency_case_id' => $this->emergency_case_id,
            'evolution' => $this->evolution,
            'status' => $this->evolution_status,
            'created_at' => $this->evolution_created_at,
            'updated_at' => $this->evolution_updated_at,
        ];
    }
}
