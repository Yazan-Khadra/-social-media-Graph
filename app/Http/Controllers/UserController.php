<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Client\Events\RequestSending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller{
    // get User Profile Info 
    function Get_User_Profile_Info() {
        try {
            
            $user_info=JWTAuth::parsetoken()->authenticate()->makeHidden(['email','mobile_number','password']);
            return response()->json($user_info,200);
        }
        catch(JWTException $e){
            return response()->json($e->getMessage(),401);
        }
    }
    //add nullable info like bio,cv,hobbies,etc...
    public function Fill_Profile_Info(Request $request) {
        // declare response array
        $response = [];
        $validation = Validator::make($request->all(),[
            "bio" => 'string|max:255',
            "cv_url" => 'string|file|mimes:pdf',
            "links" => 'array',
        ]);
        if($validation->fails()) {
            return response()->json($validation->errors(),422);
        }
        // update user info
       $user = Auth::user();

        User::where("id",$user->id)->update([
            "profile_image"=>$request->profile_image?:null,
            "bio" => $request->bio ?: null,
            "cv_url" => $request->cv_url ?: null,
            "social_links" => $request->links ?: null
        ]);
        $response [] = [
            "message" => "data added sucsessfully"
        ];
        return response()->json($response,202);
    }
    //update the social links
    public function Update_Social_Links(Request $request) {
        $validation = Validator::make($request->all(),[
            "links" => "required|array",
        ]);
        if($validation->fails()){
            return response()->json($validation->errors(),422);
        }
        //get the user
        $user = User::findOrFail(Auth::user()->id);
        $updated_social_links = $user->social_links;
    
        foreach($request->links as $updated_link){
            
            foreach($updated_social_links as &$link) {
            
            if($link['id'] === $updated_link['id']){
                $link['link'] = $updated_link['link'];
            }
         }
    }
    
    
    $user->social_links = $updated_social_links;
    $user->save();
        $response  = [
            "message" => "data updated successfully"
        ];
        return response()->json($response,202);
}
public function Delete_social_link(Request $request) {
     $validation = Validator::make($request->all(),[
            "links" => "required|array",
        ]);
        if($validation->fails()){
            return response()->json($validation->errors(),422);
        }
        //get the user
        $user = User::findOrFail(Auth::user()->id);
        $social_links = $user->social_links;
    
        foreach($request->links as $deleted_link){
            //use array filter higher order function to delete filter the array
            $new_arrayLinks = array_filter($social_links, function($link)  use ($deleted_link) {
                return $link['id']!=$deleted_link['id'];
            });
    }
    $user->social_links = $new_arrayLinks;
    $user->save();
        $response  = [
            "message" => "data deleted successfully"
        ];
        return response()->json($response,200);

}
}
