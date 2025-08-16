<?php

namespace App\Http\Controllers;

use App\JsonResponseTrait;
use App\Models\commentResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentResponsesController extends Controller
{
    use JsonResponseTrait;
    public function Response_To_Comment(Request $request) {
        $validation = Validator::make($request->all(),[
            'comment' => 'required|string',
        ]);
        if($validation->fails()) {
            return $this->JsonResponse($validation->errors(),422);

        }
        commentResponses::create([
            'comment' => $request->comment,
            'comment_id' => $request->main_comment_id,
            'user_id' => Auth::user()->id
        ]);
        return $this->JsonResponse("comment Response added succsesfully",200);
    }
    public function Delete_Response($response_id) {
        commentResponses::where('id',$response_id)->delete();
        return $this->JsonResponse('deleted',200);
    }
    public function Update_Response(Request $request) {
          commentResponses::where('id',$request->response_id)->update([
            'comment' => $request->comment
          ]);
          return $this->JsonResponse("comment updated Successfully",202);
    }
}
