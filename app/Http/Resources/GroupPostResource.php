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
        $skills = [];
        $members = [];
        foreach($this->skills as $skill ){
        $skills[] = [
            "id" => $skill->id,
            "name" => $skill->name,
            "logo_url" => 'http://127.0.0.1:8000/api'.$skill->logo_url,
            
        ];
    }
    
    foreach($this->Group->members as $member){
        $members[] = [
            "id" => $member->id,
            "name" => $member->first_name . " " . $member->last_name,
            "profile_image_url" => 'http://127.0.0.1:8000/api'.$member-> profile_image_url,
            "is_admin" => $member->pivot->is_admin,
        ];
    }
    $project = Project::where('id',$this->group->id)->get()->first();
    $group = [
        "id" => $this->Group->id,
        "group_name" => $this->Group->group_name,
        'project_id' => $project->id,
        "project" => $project->name
    ];



        return [
            'id' => $this->id,
            "description" => $this->description,
            "group" => $group,
            "skills" => $skills,
            "group_members" => $members,
        ];
    }
}
