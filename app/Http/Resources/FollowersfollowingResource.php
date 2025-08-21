<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FollowersfollowingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        $user_info = null;
        if($this->role ==="student") {
            $user_info = $this->Student;
            return [
                'id' => $user_info->id,
                'name' => $user_info->first_name . " " . $user_info->last_name,
                'profile_image_url' => 'http://127.0.0.1:8000'.$user_info->profile_image_url,
                'year' => $user_info->year,
                'major' =>$user_info->major,
                'role' => $this->role
            ];
        }
        else if($this->role ==="company") {
            $user_info = $this->Company;
            return [
                'id' => $user_info->id,
                'name' => $user_info->company_name,
                'profile_image_url' => 'http://127.0.0.1:8000'.$user_info->logo_url,
                'role' => $this->role
            ];
        }
        else {
            $user_info = $this->Staff;
            return [
                'id' => $user_info->id,
                'name' => $user_info->first_name . " " . $user_info->last_name,
                'profile_iamge_url' => 'http://127.0.0.1:8000'.$user_info->profile_image_url
            ];
        }
       
    }
}
