<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Year;
use App\JsonResponseTrait;

class YearController extends Controller
{
    use JsonResponseTrait;

    public function show_all_years()
    {
        $years = Year::all();
        if ($years->isEmpty()) {
            return $this->JsonResponse("No years found in the database", 200);
        }

        $yearsWithCount = $years->map(function($year) {
            return [
                'id' => $year->id,
                'name' => $year->Year_name,
                'total_students' => User::where('year_id', $year->id)->where('role', 'student')->count()
            ];
        });

        return $this->JsonResponseWithData(
            "Available years with student counts",
            $yearsWithCount,
            200
        );
    }

    public function getStudentsByYear($id)
    {
        // Get the year with its students
        $year = Year::with(['users' => function($query) {
            $query->where('role', 'student')
                  ->select('id', 'first_name', 'last_name', 'email', 'profile_image_url')
                  ->with('major:id,Major_name');
        }])->find($id);
    

        if (!$year) {
            return $this->JsonResponse("Year not found", 404);
        }
        
        // Format students data
        $students = $year->users->map(function($student) {
            return [
                'id' => $student->id,
                'name' => $student->first_name . ' ' . $student->last_name,
                'email' => $student->email,
                'profile_image' => $student->profile_image,
                'major' => $student->major ? $student->major->Major_name : null
            ];
        });

        return $this->JsonResponseWithData(
            "Students in " . $year->Year_name,
            $students,
            200
        );

    }

}
