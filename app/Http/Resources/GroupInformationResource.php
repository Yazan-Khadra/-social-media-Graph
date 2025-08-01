<?php

namespace App\Http\Resources;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $group_project = Project::where('id',$this->project_id)->pluck('name');
        
        return [
            'id' => $this->id,
            'group_name' => $this->group_name,
            'group_project' => $group_project[0],
            'is_admin' => $this->pivot->is_admin,
        ];
    }
}
