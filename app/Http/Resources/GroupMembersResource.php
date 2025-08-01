<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupMembersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->first_name ." " . $this->last_name,
            'image' => $this->profile_image_url,
            'skills' => $this->skills,
            'is_admin' => $this->pivot->is_admin
        ];
    }
}
