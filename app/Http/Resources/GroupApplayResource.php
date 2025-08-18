<?php

namespace App\Http\Resources;

use App\Models\Skill;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupApplayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $skill = Skill::where('id',$this->skill_id)->get()->first();
        $student = Student::where('id',$this->student_id)->get()->first();
        
        return [
            'applay_id' => $this->id,
            "student" => [
            "student_id" => $student->id,
             "student_name" => $student->first_name . " " . $student->last_name,
             "image" => 'http://127.0.0.1:8000/api'.$student->profile_image_url,
            ],
            "skill" => [
             "skill_id" =>$skill->id,
             "skill_name" => $skill->name,
             "skill_logo" => $skill->logo_url
            ]
        ];
    }
}
