<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\JsonResponseTrait;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    use JsonResponseTrait;
    public function make_comment(Request $request) {
        $validation = Validator::make($request->all(),[
            'comment' => 'required|string'
        ]);
        if ($validation->fails()) {
            return $this->JsonResponse($validation->errors(),422);
        }
        Comment::create([
            "comment" => $request->comment,
            "post_id" => $request->post_id,
            "user_id" => Auth::user()->id
        ]);
        return $this->JsonResponse("comment added successfully",200);
    }
      public function Get_Post_comments($post_id) {
        $post = Post::where('id',$post_id)->get()->first();
        $comments = $post->Comments;
        return CommentResource::collection($comments);
        
    }

   public function Delete_Comment($comment_id) {
        Comment::where('id',$comment_id)->delete();
        return $this->JsonResponse("deleted Successfully",200);
   }
   public function Update_Comment(Request $request) {
    Comment::where('id',$request->comment_id)->update([
        'comment' => $request->comment,
    ]);
    return $this->JsonResponse("comment updated successfully",202);
   }
    
}
