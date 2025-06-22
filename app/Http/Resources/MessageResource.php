<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'user_fullname' => $this->user->name . ' ' . $this->last_name,
            'user_photo' => $this->user->photo,
            'body' => $this->body,

        ];
    }
}
