<?php

namespace App\Http\Controllers;

use App\JsonResponseTrait;
use App\Models\Comment;
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
}
