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
        return $this->JsonResponseWithData("This is the years list", $years, 200);
    }

    // get all students of a specific year
    public function show_students_by_year($id)
    {
        $year = Year::find($id);
        $students = $year->users()
            ->select('id', 'first_name', 'last_name', 'email', 'profile_image', 'year_id')
            ->get();

        return $this->JsonResponseWithData(
            "Students in " . $year->Year_name,
            $students,
            200
        );
    }
}
