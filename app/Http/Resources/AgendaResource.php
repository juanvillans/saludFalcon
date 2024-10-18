<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgendaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            "specialty_id" => $this->id,
            "specialty_name" => $this->name,
            "calendar" => $this->formatToCalendar($this->doctors),


        ];
    }

    private function formatToCalendar($doctorsAndServices){

        $result = $doctorsAndServices->map(function ($register){

            return [
                'id' => $register->pivot->id,
                'doctor_name' => $register->name,
                'doctor_last_name' => $register->last_name,
                'title' => $register->pivot->title,
                'description' => $register->pivot->description,

            ];
        });

        return $result;

    }
}


