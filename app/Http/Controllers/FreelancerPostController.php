<?php

namespace App\Http\Controllers;

use App\Models\FreelancerPost;
use App\Models\Company;
use App\Models\Skill;
use App\Models\WorkPlace;
use App\Models\JobType;
use App\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FreelancerPostController extends Controller
{
    use JsonResponseTrait;


    // add post of one company
    public function add_post(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
            'skill_id' => 'required|exists:skills,id',
            'work_places' => 'array|exists:work_places,id',
            'job_types' => 'array|exists:job_types,id'
        ]);

        if ($validator->fails()) {
            return $this->JsonResponse($validator->errors(), 422);
        }

        // Get the authenticated user's company
        $company = Company::where('email', $user->email)->first();

        $post = FreelancerPost::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'company_id' => $company->id,
            'skill_id' => $request->skill_id
        ]);


        return $this->JsonResponseWithData('Post created successfully', [
            'id' => $post->id,
            'title' => $post->title,
            'description' => $post->description,
            'status' => $post->status,
            'company_id' => $post->company_id,
            'company_name' => $post->company->name, 
            'skill_id' => $post->skill_id,
        ], 201);
    }

    public function delete_post($id)
    {
        $user = Auth::user();
        $post = FreelancerPost::findOrFail($id);

        // Check if the authenticated user owns the company that created this post
        $company = Company::where('email', $user->email)->first();
        if (!$company || $post->company_id !== $company->id) {
            return $this->JsonResponse('Unauthorized. You can only delete posts from your own company.', 403);
        }

        $post->delete();
        return $this->JsonResponse('Post deleted successfully',200);
    }

    public function update_post(Request $request, $id)
    {
        $user = Auth::user();
        $post = FreelancerPost::findOrFail($id);

        // Check if the authenticated user owns the company that created this post
        $company = Company::where('email', $user->email)->first();
        if (!$company || $post->company_id !== $company->id) {
            return $this->JsonResponse('Unauthorized. You can only update posts from your own company.', 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'description' => 'string',
            'status' => 'in:active,inactive',
            'company_id' => 'exists:companies,id',
            'skill_id' => 'exists:skills,id',
            'work_places' => 'array|exists:work_places,id',
            'job_types' => 'array|exists:job_types,id'
        ]);

        if ($validator->fails()) {
            return $this->JsonResponse($validator->errors(), 422);
        }

        $post->update([
            'title' => $request->title ?? $post->title,
            'description' => $request->description ?? $post->description,
            'status' => $request->status ?? $post->status,
            'company_id' => $request->company_id ?? $post->company_id,
            'skill_id' => $request->skill_id ?? $post->skill_id
        ]);

        return $this->JsonResponse('Post updated successfully', 200);
    }

    public function get_all_posts()
    {
        $posts = FreelancerPost::all();
        return $this->JsonResponseWithData('All freelancer posts retrieved successfully',$posts,200);
    }

    public function get_post_of_company($id)
    {
        $posts = FreelancerPost::where('company_id', $id)->get();
        return $this->JsonResponseWithData('All posts for this company retrieved successfully', $posts, 200);
    }


}
