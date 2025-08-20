<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
          $reactions_count = $this->Reactions->count();
          $comments_count = $this->Comments->count();
          $is_admin = Auth::user()->id ===$this->admin_id ? 1 : 0;
          
         $users = $this->Students->map(function ($user){
            return [
                
                'id' => $user->id,
                'name' => $user->first_name ." ".  $user->last_name,
                'profile_image_url' => 'http://127.0.0.1:8000'.$user->profile_image_url
                
            ];
         });
        if($this->project_id == null){
           
            return [
                'post_id' => $this->id,
                'description'=>$this->description,
                'files' => $this->files?:null,
                'users' => $users,
                'privacy' =>$this->privacy, 
                'created_at' =>$timeAgo,
                'reactions_count' => $reactions_count,
                'comments_count' =>$comments_count,
                'is_admin' => $is_admin
                
            ];
        }
        $project = $this->Project->name;
        return [
            'post_id' => $this->id,
            'description' =>$this->description,
            'files' => $this->files,
            'title' => $this->title,
            'project' => $project,
            'users' => $users,
            'created_at' => $timeAgo,
            'reactions_count' => $reactions_count,
            'comments_count' => $comments_count,
            'is_admin' => $is_admin
        ];
    }
}
