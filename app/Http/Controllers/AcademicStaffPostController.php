<?php

namespace App\Http\Controllers;

use App\Models\AcademicPost;
use App\Models\AcademicStaff;
use App\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AcademicStaffPostController extends Controller
{
    use JsonResponseTrait;

    // Add post for authenticated academic staff
    public function add_post(Request $request)
    {

        $user = Auth::user();
        $academy = AcademicStaff::where('user_id', $user->id)->first();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->JsonResponse($validator->errors(), 422);
        }

        $post = AcademicPost::create([
            'title' => $request->title,
            'description' => $request->description,
            'academic_staff_id' => $academy->id,
        ]);

        return $this->JsonResponseWithData('Post created successfully', [
            'id' => $post->id,
            'title' => $post->title,
            'description' => $post->description,
            'academic_staff_id' => $post->academic_staff_id,
            'academic_staff_name' => $post->academicStaff->first_name . ' ' . $post->academicStaff->last_name,
        ], 201);
    }

    public function delete_post($id)
    {
        $user = Auth::user();
        $academy = AcademicStaff::where('user_id', $user->id)->first();
        $post = AcademicPost::findOrFail($id);

        // Check if the authenticated staff owns this post
        if ($post->academic_staff_id !== $academy->id) {
            return $this->JsonResponse('Unauthorized. You can only delete your own posts.', 403);
        }

        $post->delete();
        return $this->JsonResponse('Post deleted successfully', 200);
    }

    public function update_post(Request $request, $id)
    {
        $user = Auth::user();
        $academy = AcademicStaff::where('user_id', $user->id)->first();
        $post = AcademicPost::findOrFail($id);

        // Check if the authenticated staff owns this post
        if ($post->academic_staff_id !== $academy->id) {
            return $this->JsonResponse('Unauthorized. You can only update your own posts.', 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'description' => 'string',
        ]);

        if ($validator->fails()) {
            return $this->JsonResponse($validator->errors(), 422);
        }

        $post->update([
            'title' => $request->title ?? $post->title,
            'description' => $request->description ?? $post->description,
        ]);

        return $this->JsonResponse('Post updated successfully', 200);
    }

   public function get_all_posts()
{
    $posts = AcademicPost::with('academicStaff')
        ->get()
        ->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'description' => $post->description,
                'academic_staff_id' => $post->academic_staff_id,
                'academic_staff_name' => $post->academicStaff->first_name . ' ' . $post->academicStaff->last_name,
            ];
        });

    return $this->JsonResponseWithData(
        'All academic posts retrieved successfully',
        $posts,
        200
    );
}


    public function get_posts_of_academic_staff($id)
{

    $posts = AcademicPost::with('academicStaff')
        ->where('academic_staff_id', $id)
        ->get()
        ->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'description' => $post->description,
                'academic_staff_id' => $post->academic_staff_id,
                'academic_staff_name' => $post->academicStaff->first_name . ' ' . $post->academicStaff->last_name,
            ];
        });

    return $this->JsonResponseWithData(
        'All posts for this academic staff retrieved successfully',
        $posts,
        200
    );
}

}
