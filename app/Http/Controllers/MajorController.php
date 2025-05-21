<?php

namespace App\Http\Controllers;
use App\JsonResponseTrait;
use App\Models\User;
use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    use JsonResponseTrait;
    public function show_all_majors(){
        $majors=Major::all();
        return $this->JsonResponseWithData("This is the majors list", $majors, 200);
    }
    public function show_student_by_major($id){
        $major=Major::find($id);
        if(!$major){
              return $this->JsonResponse("Major not found", 404);
        }
        $students=$major->users()
            ->select('id', 'first_name', 'last_name', 'email', 'profile_image', 'year_id')
            ->get();

          return $this->JsonResponseWithData(
            "Students in " . $major->major_name,
            $students,
            200
        );

    }
    
}
