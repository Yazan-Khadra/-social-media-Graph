<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReactionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user_info = null;
        $user_data = [];
        if($this->user->role ==="student"){
            $user_info= $this->user->Student;
            $user_data [] = [
                'id' => $user_info->id,
                'name' => $user_info->first_name . " " . $user_info->last_name,
                'profile_image_url' => 'http://127.0.0.1:8000'.$user_info->profile_image_url,
                'role' => $this->user->role
            ];
        }
        else if($this->user->role ==="student"){
            $user_info= $this->user->Company;
              $user_data [] = [
                'id' => $user_info->id,
                'name' => $user_info->name,
                'profile_image_url' => 'http://127.0.0.1:8000'.$user_info->logo_url,
                'role' => $this->user->role
            ];
        }
        else {
            $user_info = $this->user->Staff;
              $user_data [] = [
                'id' => $user_info->id,
                'name' => $user_info->first_name . " " . $user_info->last_name,
                'profile_image_url' => 'http://127.0.0.1:8000'.$user_info->profile_image_url,
                'role' => $this->user->role
            ];
        }
        
        
        return [
            'reaction_id' => $this->reaction_id,
            'reaction' => $this->reaction->reaction,
            'user' => $user_data
            ];
    }
}
