<?php

namespace App\Http\Controllers;

use App\Models\FreelancerPost;
use App\Models\Company;
use App\Http\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FreelancerPostController extends Controller
{
    use JsonResponseTrait;


    // add post of one comnapy
    public function add_post(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'skill_id'=>'required',
        ]);
        $post = FreelancerPost::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'company_id' => $user->id,

        ]);

        return $this->JsonResponse($post, 'Post created successfully.', 201);
    }

    public function delete_post($id)
    {
        $user = Auth::user();
        $post = FreelancerPost::find($id);

        if (!$post) {
            return $this->JsonResponse('Post not found.', 422);
        }

        // Check if the company owns the post
        if ($post->company_id !== $user->id) {
            return $this->JsonResponse('Unauthorized. this post is not for you.', 403);
        }

        $post->delete();
        return $this->JsonResponse(null, 'Post deleted successfully.');
    }

    public function update_post(Request $request, $id)
    {
        $user = Auth::user();
        $post = FreelancerPost::find($id);


        if (!$post) {
            return $this->JsonResponse('Post not found.', 404);
        }

        // Check if the company owns the post
        if ($post->company_id !== $company->id) {
            return $this->JsonResponse('Unauthorized. you can not edit this post', 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'description' => 'string',
            'status' => 'string|in:active,closed',
        ]);

        if ($validator->fails()) {
            return $this->JsonResponse($validator->errors(), 422);
        }

        $post->update($validator->validated());
        return $this->JsonResponse($post, 'Post updated successfully.');
    }

    public function get_all_posts()
    {
        $posts = FreelancerPost::all();
        return $this->JsonResponseWithData('All freelancer posts retrieved successfully',$posts,200);
    }

    // Get all posts of one company
    public function get_company_posts()
    {
        $user = Auth::user();
        $posts = FreelancerPost::where('company_id', $user->id)->get();
        return $this->JsonResponseWithData('All posts for this company retrieved successfully', $posts, 200);
    }
}
