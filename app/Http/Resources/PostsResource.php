<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         $users = $this->users;
        if($this->project_id == null){
           
            return [
                'description'=>$this->description,
                'files' => $this->files?:null,
                'users' => $users,
                
            ];
        }
        $project = $this->Project->name;
        return [
            'description' =>$this->description,
            'files' => $this->files,
            'title' => $this->title,
            'project' => $project,
            'users' => $users
        ];
    }
}
