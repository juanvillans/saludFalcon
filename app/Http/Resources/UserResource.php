<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
                "ci" => $this->ci,
                "name" => $this->name,
                "last_name" => $this->last_name,
                "email" => $this->email,
                "phone_number" => $this->phone_number,
                "created_at" => $this->created_at,
                "specialty" => $this->specialty,
                "specialty_id" => $this->specialty->id,
                "role_id" => $this->roles[0]->id,
                "role_name" => $this->roles[0]->name,
                "medical_license" => $this->medical_license,
                "photo" => $this->photo,
              
              
        ];
    }
}
