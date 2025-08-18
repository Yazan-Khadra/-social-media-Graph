<?php

namespace App\Http\Resources;

use App\Models\Major;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       
        $year = Year::where('id',$this->year_id)->pluck("year_name");
       
        $major = null;
        if($this->major !==null) {
            $major = Major::where('id',$this->major_id)->pluck("major_name");
        }
        return [
            'id' => $this->id,
            "name" => $this->first_name . " " . $this->last_name,
            "year" => $year,
            "major" =>$major,
            "bio" => $this->bio,
            "gender" => $this->gender,
            "cv" => $this->cv_url,
            "image" => 'http://127.0.0.1:8000/api'.$this->profile_image_url,
            "social_links" => $this->social_links,
            "rate" => $this->rate,
            "skills" => $this->skills,
            "group_id" => $this->group_id,

        ];
    }
}
