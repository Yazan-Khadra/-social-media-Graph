<?php

namespace App\Http\Resources;

use App\Models\AcademicStaff;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class identityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
       $user_info = AcademicStaff::where('id',$this->user_id)->get()->first();
      
        return [
            'identity_id' => $this->id,
            'image_url' => 'http://127.0.0.1:8000'.$this->image_url,
            'status' => $this->status,
            'user' => [
                'id' => $user_info->id,
                'name' => $user_info->first_name . " " . $user_info->last_name,
                
            ]
        ];
    }
}
 