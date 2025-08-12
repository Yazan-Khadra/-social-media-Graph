<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupPostResource;
use App\JsonResponseTrait;
use App\Models\Group;
use App\Models\GroupPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupPostController extends Controller
{
    use JsonResponseTrait;
    public function Create_Post(Request $request) {
        try {
        $validation = Validator::make($request->all(),[
            "description" => 'required|string',
            "skills_id" => 'required|array',
        ]);
        if($validation->fails()) {
            return $this->JsonResponse($validation->errors(),422);
        }
        
        
        $post=GroupPost::create([
            "description" => $request->description,
            "admin_id" => $request->admin_id,
            "group_id" => $request->group_id,
            
        ]); 
        $post->skills()->attach($request->skills_id);
        return $this->JsonResponse("data added sccessfully",200);
    } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error while updating Data',
                'error' => $e->getMessage()
            ], 500);
        }
        
    }
    public function Get_Groups_Posts(){
        $posts = GroupPost::with([
            'skills',
            'group.members',
        ])->get();
        return GroupPostResource::collection($posts);
    }
    public function Delete_Post($post_id) {
        GroupPost::where('id',$post_id)->delete();
        return $this->JsonResponse("Post Deleted Successfully",200);
    }
    
}
