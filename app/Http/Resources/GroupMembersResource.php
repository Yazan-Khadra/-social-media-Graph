<?php

namespace App\Http\Resources;

use App\Models\Skill;
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
        $skill = Skill::where('id',$this->pivot->skill_id)->get()->first();
        
        return [
            'name' => $this->first_name ." " . $this->last_name,
            'image' => $this->profile_image_url,
            'skill' =>[
                "skill_name" => $skill->name,
                "skill_logo" => $skill->logo_url
            ],
            'is_admin' => $this->pivot->is_admin
        ];
    }
}
