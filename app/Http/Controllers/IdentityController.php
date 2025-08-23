<?php

namespace App\Http\Controllers;

use App\Http\Resources\identityResource;
use App\JsonResponseTrait;
use App\Models\identity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class IdentityController extends Controller
{
    use JsonResponseTrait;
    public function Set_info(Request $request) {
        $validation = Validator::make($request->all(),[
            'image' => 'required|file|mimes:png,jpg'
        ]);
        if($validation->fails()) {
            return $this->JsonResponse($validation->errors(),422);
        }
          $identity = null;
       if($request->hasFile("image")){
        $path = $request->image->store('identity','public');
         $identity = '/storage/' . $path;
       }

        identity::create([
            'user_id' =>Auth::user()->id,
            'image_url' =>$identity,
        ]);
    }
    public function Get_Pending_orders(){
        $identities=identity::where('status','pending')->get();
        
        return identityResource::collection($identities);
    }
    public function Response_to_order(Request $request) {
        if($request->response==="reject"){
            identity::where('id',$request->identity_id)->delete();
            User::where('id',$request->user_id)->delete();
        }
        else {
            identity::where('id',$request->identity_id)->delete();
        }
        return $this->JsonResponse("done",200);
    }
}
