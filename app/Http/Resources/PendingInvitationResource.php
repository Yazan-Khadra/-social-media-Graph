<?php

namespace App\Http\Resources;

use App\Models\Project;
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
        
        $admin = User::where('id',$group_info->admin_id)->get()->first();
       
        $project = Project::where('id',$group_info->project_id)->pluck('name');
        return [
            'group_name' =>$group_info->group_name,
            'sender_user' =>$admin->first_name . " " .$admin->last_name,
            'project' => $project[0],

        ];
    }
}
