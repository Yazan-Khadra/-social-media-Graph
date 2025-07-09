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
          $timeAgo = $this->created_at ? $this->created_at->diffForHumans() : null;
         $users = $this->users->map(function ($user){
            return [
                'id' => $user->id,
                'name' => $user->first_name ." ".  $user->last_name,
                
            ];
         });
        if($this->project_id == null){
           
            return [
                'description'=>$this->description,
                'files' => $this->files?:null,
                'users' => $users,
                'privacy' =>$this->privacy, 
                'created_at' =>$timeAgo
                
            ];
        }
        $project = $this->Project->name;
        return [
            'description' =>$this->description,
            'files' => $this->files,
            'title' => $this->title,
            'project' => $project,
            'users' => $users,
            'created_at' => $timeAgo,
        ];
    }
}
