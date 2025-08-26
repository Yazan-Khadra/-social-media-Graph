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
        'work_places' => 'array',
        'work_places.*' => 'exists:work_places,id',
        'job_types' => 'array',
        'job_types.*' => 'exists:job_types,id',
    ]);

    if ($validator->fails()) {
        return $this->JsonResponse($validator->errors(), 422);
    }

    // Get the authenticated user's company
    $company = Company::where('user_id', $user->id)->first();

    $post = FreelancerPost::create([
        'title' => $request->title,
        'description' => $request->description,
        'status' => $request->status,
        'company_id' => $company->id,
        'skill_id' => $request->skill_id,
    ]);


    if ($request->has('work_places')) {
        $post->workPlaces()->sync($request->work_places);
    }


    if ($request->has('job_types')) {
        $post->jobTypes()->sync($request->job_types);
    }

    return $this->JsonResponseWithData('Post created successfully', [
        'id' => $post->id,
        'title' => $post->title,
        'description' => $post->description,
        'status' => $post->status,
        'company_id' => $post->company_id,
        'company_name' => $post->company->company_name,
        'work_places' => $post->workPlaces->pluck('name'),
        'job_types' => $post->jobTypes->pluck('name'),
    ], 201);
}


    public function delete_post($id)
    {
        $user = Auth::user();
        $post = FreelancerPost::findOrFail($id);

        // Check if the authenticated user owns the company that created this post
        $company = Company::where('user_id', $user->id)->first();
        if (!$company || $post->company_id !== $company->id) {
            return $this->JsonResponse('Unauthorized. You can only delete posts from your own company.', 403);
        }

        $post->delete();
        return $this->JsonResponse('Post deleted successfully',200);
    }

    public function update_post(Request $request, $id)
{
    $user = Auth::user();
    $post = FreelancerPost::find($id);

    if (!$post) {
        return $this->JsonResponse('Post not found', 404);
    }

    // Check if the authenticated user owns the company that created this post
    $company = Company::where('user_id', $user->id)->first();
    if (!$company || $post->company_id !== $company->id) {
        return $this->JsonResponse('Unauthorized. You can only update posts from your own company.', 403);
    }

    $validator = Validator::make($request->all(), [
        'title' => 'string|max:255',
        'description' => 'string',
        'status' => 'in:active,inactive',
        'skill_id' => 'exists:skills,id',
        'work_places' => 'array',
        'work_places.*' => 'exists:work_places,id',
        'job_types' => 'array',
        'job_types.*' => 'exists:job_types,id',
    ]);

    if ($validator->fails()) {
        return $this->JsonResponse($validator->errors(), 422);
    }

    // تحديث الحقول الرئيسية
    $post->update([
        'title' => $request->title ?? $post->title,
        'description' => $request->description ?? $post->description,
        'status' => $request->status ?? $post->status,
        'skill_id' => $request->skill_id ?? $post->skill_id,
    ]);

    // تحديث العلاقات many-to-many
    if ($request->has('work_places')) {
        $post->workPlaces()->sync($request->work_places);
    }

    if ($request->has('job_types')) {
        $post->jobTypes()->sync($request->job_types);
    }

    return $this->JsonResponseWithData('Post updated successfully', [
        'id' => $post->id,
        'title' => $post->title,
        'description' => $post->description,
        'status' => $post->status,
        'company_id' => $post->company_id,
        'company_name' => $post->company->company_name,
        'skill_id' => $post->skill_id,
        'work_places' => $post->workPlaces->pluck('name'),
        'job_types' => $post->jobTypes->pluck('name'),
    ], 200);
}
public function get_all_posts()
{
    $posts = FreelancerPost::with(['company', 'skill', 'workPlaces', 'jobTypes'])
    ->where('created_at', '>=', Carbon::now()->subDays(3)) 
    ->orderBy('created_at', 'desc')
    ->get();

    $data = $posts->map(function ($post) {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'description' => $post->description,
            'status' => $post->status,

            'company_id' => $post->company_id,
            'company_name' => $post->company?->company_name,

            'skill_id' => $post->skill_id,
            'skill_name' => $post->skill?->name,

            'work_place_ids' => $post->workPlaces->pluck('id'),
            'work_place_names' => $post->workPlaces->pluck('name'),

            'job_type_ids' => $post->jobTypes->pluck('id'),
            'job_type_names' => $post->jobTypes->pluck('name'),
        ];
    });

    return $this->JsonResponseWithData('All freelancer posts retrieved successfully', $data, 200);
}


public function get_post_of_company($id)
{
    $posts = FreelancerPost::with(['company', 'skill', 'workPlaces', 'jobTypes'])
        ->where('company_id', $id)
        ->get();

    $data = $posts->map(function ($post) {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'description' => $post->description,
            'status' => $post->status,

            'company_id' => $post->company_id,
            'company_name' => $post->company?->company_name,

            'skill_id' => $post->skill_id,
            'skill_name' => $post->skill?->name,

            'work_place_ids' => $post->workPlaces->pluck('id'),
            'work_place_names' => $post->workPlaces->pluck('name'),

            'job_type_ids' => $post->jobTypes->pluck('id'),
            'job_type_names' => $post->jobTypes->pluck('name'),
        ];
    });

    return $this->JsonResponseWithData('All posts for this company retrieved successfully', $data, 200);
}



}
