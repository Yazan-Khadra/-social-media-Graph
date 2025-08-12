<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        $user = $this->user;
        $user_info = null;
        if($user->role==="student"){
            $user_info = $user->Student;
            return [
            'comment_id' => $this->id,
            "comment" => $this->comment,
            "user" => [
                "user_id" => $user_info->id,
                "name" => $user_info->first_name . $user_info->last_name,
                "image_url" => $user_info->profile_image_url,
                "role" => $user->role,
            ]
        ];
        }
        else if($user->role ==="company"){
            $user_info = $user->Company;
              return [
            'comment_id' => $this->id,
            "comment" => $this->comment,
            "user" => [
                "user_id" => $user_info->id,
                "name" => $user_info->company_name,
                "image_url" => $user_info->logo_url,
                "role" => $user->role,
            ]
        ];
        }
        
    }
}
