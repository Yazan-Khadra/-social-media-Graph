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
        $responses = $this->CommentsResponses;
        $responses_data =[];
        foreach ($responses as $response ){
            if($response->user->role ==="student") {
                $user_comment_info=$response->user->Student;
                $responses_data[] = [
                    'response_id' => $response->id,
                    'comment' =>$response->comment,
                      "user" => [
                "user_id" => $user_comment_info->id,
                "name" => $user_comment_info->first_name ." " . $user_comment_info->last_name,
                "image_url" => 'http://127.0.0.1:8000'.$user_comment_info->profile_image_url,
                
            ],
            
        ];
            }
              else if($response->user->role ==="company"){
            $user_comment_info = $response->user->Company;
              $responses_data[] = [
            'response_id' => $response->id,
            "comment" => $response->comment,
            "user" => [
                "user_id" => $user_comment_info->id,
                "name" => $user_comment_info->company_name,
                "image_url" => 'http://127.0.0.1:8000'.$user_comment_info->logo_url,
            ]
        ];
        }
        else {
            $user_comment_info = $response->user->Staff;
             $responses_data[] = [
                    'response_id' => $response->id,
                    'comment' =>$response->comment,
                      "user" => [
                "user_id" => $user_comment_info->id,
                "name" => $user_comment_info->first_name ." " . $user_comment_info->last_name,
                "image_url" => 'http://127.0.0.1:8000'.$user_comment_info->profile_image_url,
                
            ],
        ];

        }
            
        }
        $user_info = null;
        if($user->role==="student"){
            $user_info = $user->Student;
            return [
            'comment_id' => $this->id,
            "comment" => $this->comment,
            "user" => [
                "user_id" => $user_info->id,
                "name" => $user_info->first_name ." " . $user_info->last_name,
                "image_url" => 'http://127.0.0.1:8000'.$user_info->profile_image_url,
                "role" => $user->role,
            ],
            'responses' =>$responses_data
            
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
                "image_url" => 'http://127.0.0.1:8000'.$user_info->logo_url,
                "role" => $user->role,
            ],
            'responses' => $responses_data
        ];
        }
        else {
            $user_info = $user->Staff;
             return [
            'comment_id' => $this->id,
            "comment" => $this->comment,
            "user" => [
                "user_id" => $user_info->id,
                "name" => $user_info->first_name ." " . $user_info->last_name,
                "image_url" => 'http://127.0.0.1:8000'.$user_info->profile_image_url,
                "role" => $user->role,
            ],
            'responses' =>$responses_data
            
        ];

        }
        
    }
}
