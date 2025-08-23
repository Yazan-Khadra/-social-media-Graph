<?php

namespace App\Http\Controllers;

use App\Models\AcademicStaff;
use App\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AcademicStaffController extends Controller
{
    use JsonResponseTrait;

    // Get all academic staff
    public function Register_Staff_Member(Request $request) {
        $validation = Validator::make($request->all(),[
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'profile_image_url' => 'file|mimes:png,jpg'
        ]);
        if($validation->fails()) {
            return $this->JsonResponse($validation->errors(),422);
        }
          $profile_image_url = null;
       if($request->hasFile("profile_image")){
        $path = $request->profile_image->store('staff_images','public');
         $profile_image_url = '/storage/' . $path;
       }
        AcademicStaff::create([
            'id' => Auth::user()->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'profile_image' =>$profile_image_url,
            'bio' => $request->bio?:null,
            'user_id' => Auth::user()->id
        ]);
        return $this->JsonResponse("done sccessfully",200);
    }
    public function show_all_academic_staff()
    {
        $staff = AcademicStaff::select(['first_name','last_name','bio','profile_image_url'])->get();
        return $this->JsonResponseWithData('All Academic Staff:', $staff, 200);
    }

    // Get specific academic staff by ID
    public function show_one_academic_staff($id)
    {
        $staff = AcademicStaff::findOrFail($id);
        return $this->JsonResponseWithData('Academic staff retrieved successfully', $staff, 200);
    }

    // Fill academic staff info for authenticated user
    public function fill_academic_staff_info(Request $request)
    {
        $user = Auth::user();

        $validation = Validator::make($request->all(), [
            'bio' => 'nullable|string',
            'profile_image_url' => 'nullable|string',
        ]);

        if ($validation->fails()) {
            return $this->JsonResponse($validation->errors(), 400);
        }

        $staff = AcademicStaff::where('email', $user->email)->first();
        if (!$staff) {
            return $this->JsonResponse('Academic staff not found. Please register first.', 404);
        }

        $staff->update([
            'bio' => $request->bio ?: null,
            'profile_image_url' => $request->profile_image_url ?: null,
        ]);

        return $this->JsonResponse('Academic staff information added successfully', 201);
    }

   
    // Update academic staff info for authenticated user
    public function update_academic_staff_info(Request $request)
    {
        $user = Auth::user();
        $staff = AcademicStaff::where('email', $user->email)->first();
        if (!$staff) {
            return $this->JsonResponse('Academic staff not found. Please create info first.', 404);
        }
        $validation = Validator::make($request->all(), [
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'bio' => 'nullable|string',
        ]);
        if ($validation->fails()) {
            return $this->JsonResponse($validation->errors(), 400);
        }
        $staff->first_name = $request->first_name;
        $staff->last_name = $request->last_name;
        $staff->bio = $request->bio;
       
        $staff->save();
        return $this->JsonResponse('Academic staff information updated successfully', 200);
    }

    // Delete the academic staff account
    public function destroy()
    {
        $user = Auth::user();
        $user->delete();
        return $this->JsonResponse('Academic staff account deleted successfully', 200);
    }

    // Search academic staff by name or bio
    public function search(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'staff' => 'required|string'
        ]);
        if ($validation->fails()) {
            return $this->JsonResponse($validation->errors(), 400);
        }
        $staff = $request->input('staff');
        $results = AcademicStaff::where('first_name', 'LIKE', "%{$staff}%")
            ->orWhere('last_name', 'LIKE', "%{$staff}%")
            ->get();
        return $this->JsonResponseWithData('Search completed successfully', $results, 200);
    }
}
