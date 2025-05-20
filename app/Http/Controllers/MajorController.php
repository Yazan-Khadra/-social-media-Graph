<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function show_all_majors(){
        $majors=Major::all();
        return $this->JsonResponseWithData("This is the majors list", $majors, 200);
    }
    public function show_student_by_major($id)
    {
        $major = Major::find($id);

        if (!$major) {
            return $this->JsonResponse("Major not found", 404);
        }

        $students = User::where('major_id', $id)
            ->where('role', 'student')
            ->select('id', 'first_name', 'last_name', 'email', 'profile_image', 'year_id')
            ->with(['year:id,Year_name'])
            ->get()
            ->map(function($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'email' => $student->email,
                    'profile_image' => $student->profile_image,
                    'year' => $student->year ? $student->year->Year_name : null
                ];
            });

        if ($students->isEmpty()) {
            return $this->JsonResponse("No students found in this major", 200);
        }

        return $this->JsonResponseWithData(
            "Students in " . $major->Major_name,
            $students,
            200
        );
    }
}
