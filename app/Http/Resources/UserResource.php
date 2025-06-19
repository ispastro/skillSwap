<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'bio' => $this->bio,
            'city' => $this->city,
            'country' => $this->country,
            'availability' => $this->availability,
            'skills_offered' => $this->skills_offered,
            'skills_needed' => $this->skills_needed,
            'profile_picture' => $this->profile_picture ? asset('storage/' . $this->profile_picture) : null,
            'token_balance' => $this->token_balance,
        ];
    }
}