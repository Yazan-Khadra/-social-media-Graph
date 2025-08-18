<?php

namespace App\Http\Resources;

use App\Models\Project;
use App\Models\Skill;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PendingInvitationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $group_info = $this->group;
        
        $admin = Student::where('id',$group_info->admin_id)->get()->first();
        
       
        $project = Project::where('id',$group_info->project_id)->get()->first();
        $skill = Skill::where('id',$this->skill_id)->get()->first();
        return [
            "invitation_id" =>$this->id,
            'group_name' =>$group_info->group_name,
            'sender_user' =>$admin->first_name . " " .$admin->last_name,
            'project_id' => $project->id,
            'project_name' => $project->name,
            'skill' => [
                "skill_id" => $skill->id,
                'skill_name' => $skill->name,
                "logo_url" => 'http://127.0.0.1:8000/api'.$skill->logo_url,
            ],

        ];
    }
}
