<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\PostsResource;
use App\Http\Resources\UserInfoResource;
use App\JsonResponseTrait;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Http\Client\Events\RequestSending;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    use JsonResponseTrait;
    public function register(Request $request)
    {
    
        $validator = Validator::make($request->all(), [
            
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' =>'required|string|date|before:today',
            'gender' =>'required|string',
            "profile_image" => 'image|mimes:png,jpg',
            'year_id'=>'required|numeric',
            'major_id' => 'numeric',
            
        ]);
        if($request->year_id == 4 || $request->year_id == 5) {
            if(empty($request->major_id) || $request->major_id === null){
                $response = [
                    "message" => "the specialization field required for the fourth and fifth year",
                ];
                return response()->json($response,422);
            }
        }

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
          //    check if the profile image is send
          
       if($request->hasFile("profile_image")){
        $path = $request->profile_image->store('profile_images','public');
        $profile_image_url = '/storage/' . $path;
       }
     
       
       
        $user = Student::create([
            'id' => Auth::user()->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_date' => $request->birth_date,
            'profile_image_url' =>$profile_image_url,
            'gender' => $request->gender,
            'year_id' =>$request->year_id,
            'major_id' =>$request->major_id ?: null,
            'user_id' =>Auth::user()->id,
        ]);
            
            // 
        
        
        return response()->json("information added sucsessfully",201);
    }
     public function Fill_Profile_Info(Request $request) {
        try{
        // declare response array
        $response = [];
        $validation = Validator::make($request->all(),[
            "bio" => 'string|max:255',
            "cv" => 'file|mimes:pdf',
            "profile_image" => 'image|mimes:png,jpg',
        ]);
        if($validation->fails()) {
            return response()->json($validation->errors(),422);
        }
        // update user info
   
        
       $set_data = Student::findOrFail(Auth::user()->id);
       $set_data->bio = $request->bio?:null;
    //    check if the cv is send   
       if($request->hasFile("cv")){
         $path = $request->cv->store('cvs', 'public');
        $set_data->cv_url = '/storage/' . $path;
       }
    //    check if the profile image is send
       if($request->hasFile("profile_image")){
        $path = $request->profile_image->store('profile_images','public');
        $set_data->profile_image_url = '/storage/' . $path;
       }
       $set_data->save();
        $response [] = [
            "message" => "data added sucsessfully"
        ];
        return response()->json($response,202);
    }
    catch (\Exception $e) {
            return response()->json([
                'message' => 'Error while updating Data',
                'error' => $e->getMessage()
            ], 500);
        }
 }
 public function Set_Social_Links(Request $request) {
    $validation = Validator::make($request->all(),[
        "links" => "array",
    ]);
    if($validation->fails()) {
        return response()->json($validation->errors(),422);
    }
    $user = Student::findOrFail(Auth::user()->id)->update([
        "social_links" => $request->links,
    ]);
    return response()->json("social links added sucssesfully",201);
 }
    //update the social links
    public function Update_Social_Links(Request $request) {
    try {
        $validation = Validator::make($request->all(),[
            "links" => "required|array",
        ]);
        if($validation->fails()){
            return response()->json($validation->errors(),422);
        }
        //get the user
        $user = Student::findOrFail(Auth::user()->id);
    
    $user->social_links = $request->links;
    $user->save();
        $response  = [
            "message" => "data updated successfully"
        ];
        return response()->json($response,202);
    } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error while updating Socila Links',
                'error' => $e->getMessage()
            ], 500);
        }
}
// public function Delete_social_link(Request $request) {
//      $validation = Validator::make($request->all(),[
//             "links" => "required|array",
//         ]);
//         if($validation->fails()){
//             return response()->json($validation->errors(),422);
//         }
//         //get the user
//         $user = User::findOrFail(Auth::user()->id);
//         $social_links = $user->social_links;
    
//         foreach($request->links as $deleted_link){
//             //use array filter higher order function to delete filter the array
//             $new_arrayLinks = array_filter($social_links, function($link)  use ($deleted_link) {
//                 return $link['id']!=$deleted_link['id'];
//             });
//     }
//     $user->social_links = $new_arrayLinks;
//     $user->save();
//         $response  = [
//             "message" => "data deleted successfully"
//         ];
//         return response()->json($response,200);

// }
    // update bio 
    public function Update_Bio(Request $request) {
        try{
        $user = Auth::user();
        $validation = Validator::make($request->all(),[ 
            'bio' => 'required|string|max:255'
        ]
        );
        if($validation->fails()) {
            return response()->json($validation->errors(),422);
        }
        Student::findOrFail($user->id)->update([
            "bio" => $request->bio,
        ]);
        $response [] = [
            "message" => "bio updated successfully",
        ];
        return response()->json($response,202);
    
    } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error while updating Bio',
                'error' => $e->getMessage()
            ], 500);
        }
 }
 public function Update_Profile_Image(Request $request) {
   try{
   
         $validation = Validator::make($request->all(),[
        'profile_image' =>'required|image|mimes:png,jpg',
         ]);
         //validation error
         if($validation->fails()) {
            return response()->json($validation->errors(),422);
        }
         //get the Authentic user
         $user = Student::findOrFail(Auth::user()->id);
         //update user image
        if($request->hasFile('profile_image')){
         //delete the previus image from file system
            $previus_image_url = public_path($user->profile_image_url);
        if(File::exists($previus_image_url)){
            File::delete($previus_image_url);
         // store the new image in file system and DB
        
            
        }
        $path = $request->profile_image->store("profile_images","public");
            $user->profile_image_url = '/storage/' . $path ;
       
        
     }
        $user->save();
        return response()->json("profile Image Updated Sucssesfully",202);
   
    } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error in Updating Profile Image',
                'error' => $e->getMessage()
            ], 500);
        }

 }
//  delete profile image
    public function Delete_Profile_Image(){
        try{
        $user = Student::findOrFail(Auth::user()->id);
            //delete the previus image from file system
        $previus_image_url = public_path($user->profile_image_url);
        if(File::exists($previus_image_url)){
          File::delete($previus_image_url);
        }
        // set null in DB after delete
        $user->profile_image_url = null;
            $user->save();
            return response()->json("Profile Image Deleted Successfully",200);

    }
    catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting Profile Image',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function Get_User_Profile_Info() {
        
        $student_info=Student::findOrFail(Auth::user()->id);
        
        return UserInfoResource::make($student_info);
    }
    public function Get_User_post($id) {
        $student = Student::where('id',$id)->get()->first();
        
        $student_post = $student->Posts;
        // return $this->JsonResponse($student_post,200);
        
        return PostsResource::collection($student_post);
    }
}
