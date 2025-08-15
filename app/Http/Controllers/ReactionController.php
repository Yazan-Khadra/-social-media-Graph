<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReactionsResource;
use App\JsonResponseTrait;
use App\Models\Post;
use App\Models\ReactionsPivot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReactionController extends Controller
{
    use JsonResponseTrait;
    public function Make_Reaction(Request $request) {
        $validation = Validator::make($request->all(),[
            'reaction_id' => 'required'
        ]);
        if($validation->fails()) {
            return $this->JsonResponse($validation->errors(),422);
        }
        ReactionsPivot::create([
            'reaction_id' => $request->reaction_id,
            'post_id' => $request->post_id,
            'user_id' => Auth::user()->id
        ]);
        return $this->JsonResponse("reaction added successfully",201);
    }
    public function Remove_Reaction($post_id) {
        ReactionsPivot::where('post_id',$post_id)
        ->where('user_id',Auth::user()->id)
        ->delete();
        return $this->JsonResponse("reaction removed succssfully",200);
    }
    public function Update_Reaction(Request $request) {
        ReactionsPivot::where('post_id',$request->post_id)
        ->where('user_id',Auth::user()->id)
        ->update([
            'reaction_id' => $request->reaction_id
        ]);
        return $this->JsonResponse("reaction updated succssfully",202);
    }
   public function Get_Post_Reactions($post_id)
{
    $reactions = ReactionsPivot::with(['user', 'reaction'])
        ->where('post_id', $post_id)
        ->get();
        

    return ReactionsResource::collection($reactions);
}


}
