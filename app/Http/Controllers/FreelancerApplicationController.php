<?php

namespace App\Http\Controllers;

use App\JsonResponseTrait;
use App\Models\Freelancer_application;
use App\Models\FreelancerPost;
use App\Models\Company;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FreelancerApplicationController extends Controller
{
    use JsonResponseTrait;
    public function send_application(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'student_id' => 'required|exists:users,id',
            'freelance_post_id' => 'required|exists:freelancer_posts,id'
        ]);

        if ($validator->fails()) {
            return $this->JsonResponse($validator->errors(), 422);
        }

        if ($request->hasFile('cv')) {
            $path = $request->file('cv')->store('cvs', 'public');
            $data['cv'] = '/storage/' . $path;
        }

        Freelancer_application::create([
            'name' => $request->name,
            'cv' => $request->cv,
            'student_id' => $user->id,
            'freelance_post_id' => $request->freelance_post_id,
            'status' => 'pending'
        ]);

        return $this->JsonResponse('Application submitted!', 201);
    }

    // Get all applications for the authenticated user
    public function my_applications(Request $request)
    {
        $user = $request->user();
        $applications = Freelancer_application::where('student_id', $user->id)->get();
        return $this->JsonResponseWithData('Your applications retrieved successfully', $applications, 200);
    }
}
