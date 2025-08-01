<?php

namespace App\Http\Resources;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        foreach($this->skills as $skill ){
        $skills = [
            "id" => $skill->id,
            "name" => $skill->name,
            "logo_url" => $skill->logo_url,
            
        ];
    }
    foreach($this->members as $member){
        $member = [
            "id" => $this->id,
            "name" => $this->first_name + " " + $this->last_name,
            "profile_image_url" => $this-> profile_image_url,
            "is_admin" => $this->pivot->is_admin,
        ];
    }
    $project = Project::where('id',$this->group->id)->pluck("name");
    $group = [
        "id" => $this->group->id,
        "group_name" => $this->group_name,
        "project" => $project
    ];
//baraa alkobaisi
        return [
            'id' => $this->id,
            "description" => $this->description,
            "group" => $group,
            "skills" => $skills,
            "group_members" => $member,
        ];
    }
}
