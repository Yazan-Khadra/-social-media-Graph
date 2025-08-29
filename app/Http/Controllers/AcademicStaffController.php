<?php

namespace App\Http\Controllers;

use App\Models\AcademicStaff;
use App\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


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
        $staff = AcademicStaff::select(['first_name','last_name','bio','profile_image'])->get();
        return $this->JsonResponseWithData('All Academic Staff:', $staff, 200);
    }

    // Get specific academic staff by ID
    public function show_one_academic_staff($id)
    {
        $staff = AcademicStaff::find($id);
        return $this->JsonResponseWithData('Academic staff retrieved successfully', $staff, 200);
    }



    // Update academic staff info for authenticated user
    public function update_academic_staff_info(Request $request)
{
    $user = Auth::user();

    // validate input
    $validation = Validator::make($request->all(), [
        'first_name' => 'nullable|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'bio' => 'nullable|string',
    ]);

    if ($validation->fails()) {
        return $this->JsonResponse($validation->errors(), 400);
    }

    // find the staff linked to this user
    $staff = AcademicStaff::where('user_id', $user->id)->first();

    if (!$staff) {
        return $this->JsonResponse('Academic staff record not found', 404);
    }

    // update only provided fields
    if ($request->filled('first_name')) {
        $staff->first_name = $request->first_name;
    }
    if ($request->filled('last_name')) {
        $staff->last_name = $request->last_name;
    }
    if ($request->filled('bio')) {
        $staff->bio = $request->bio;
    }

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
        public function update_logo_url(Request $request) {

        $user = Auth::user();
        $academy = AcademicStaff::where('user_id', $user->id)->first();



        if (!$academy) {
            return $this->JsonResponse("academy not found. Please create academy info first.", 404);
        }

        $validation = Validator::make($request->all(), [
            'profile_image' => 'image|mimes:png,jpg',
        ]);

        if ($validation->fails()) {
            return $this->JsonResponse($validation->errors(), 422);
        }

        // Delete the previous image from file system
        if ($academy->profile_image) {
            $previous_image_url = public_path($academy->profile_image);
            if (File::exists($previous_image_url)) {
                File::delete($previous_image_url);
            }
        }

        // Store the new image
        if ($request->hasFile('profile_image')) {
            $path = $request->profile_image->store("profile_image", "public");
            $academy->profile_image = '/storage/' . $path;
        }

        $academy->save();
        return $this->JsonResponse("Logo updated successfully", 200);
    }

    public function delete_logo_url() {

        $user = Auth::user();
        $academy = AcademicStaff::where('user_id', $user->id)->first();


        if (!$academy) {
            return $this->JsonResponse("academy not found. Please create academy info first.", 404);
        }

        // Delete the previous logo from file system
        if ($academy->profile_image) {
            $previous_image_url = public_path($academy->profile_image);
            if (File::exists($previous_image_url)) {
                File::delete($previous_image_url);
            }
        }

        // Set null in DB after delete
        $academy->profile_image = null;
        $academy->save();

        return $this->JsonResponse("Logo deleted successfully", 200);
    }

}
