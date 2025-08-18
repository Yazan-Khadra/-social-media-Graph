<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostsResource;
use App\JsonResponseTrait;
use App\Models\Post;
use App\Models\Post_User_Pivot;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    use JsonResponseTrait;
        // create new post 
    public function Create_Post(Request $request) {
        try{
        $validation = Validator::make($request->all(),[
            'description' =>'required|string',
            'files' => 'array',
            'title' =>'string|max:1500',
            'privacy' => 'required|in:public,followers',
        ]);
        if($validation->fails()) {
            return $this->JsonResponse($validation->errors(),422);
        }
        $user = Auth::user();
        $files = [];
        //store the files (images,videos,etc....)
        if($request->has('files')) {
   
            foreach($request->file('files') as $file){

              $path = $file->store('post_image', 'public');
              array_push($files,'/storage/' . $path);
              
            }
        }
        
       
        
    //    append the data on the post table
       $post = Post::create([
            'description' => $request->description,
            'files' => $files?:null,
            'title' =>$request->title?:null,
            'project_id' =>$request->project_id?:null,
            'privacy' =>$request->privacy =="public"?"public" :"followers",
            'admin_id' => Auth::user()->id

        ]);
        // check if the post is tags with many users
        if($request->input('ids')){
        $users = $request->input('ids');
        array_push($users,$user->id);
        }
        // if not only the user creator will own this post
        else {
            $users = [$user->id];
        }
        // make array_map to avoid the database heigh load
        $users_list = array_map(function($user_id) use ($post){
                return [
                    'user_id' =>$user_id,
                    'post_id' => $post->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
        },$users);
        // just one request to the database
       DB::table('_posts__users__pivot')->insert($users_list);
        
        return $this->JsonResponse("post created Sucsessfuly",201);
     }
       catch (\Exception $e) {
            return response()->json([
                'message' => 'Error while updating Data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function Get_Posts($id) {
//         $posts = Post::with(['users' => function ($query) {
//     $query->select('id','first_name', 'last_name')
//     ; // Must include 'id' to maintain relation    
// }])->get();
$user = User::findOrFail(id: $id);

  $followingIds = $user->followings->pluck('users.id');
  $followingIds->add($user->id);

$posts = Post::where(function($query) use ($followingIds) {
        $query->where('privacy', 'public')
              ->orWhere(function($q) use ($followingIds) {
                  $q->where('privacy', 'followers')
                    ->whereHas('Students', function($subQuery) use ($followingIds) {
                        $subQuery->whereIn('students.id', $followingIds);
                    });
              });
    })
    ->where('created_at', '>=', Carbon::now()->subDays(3))
    ->orderBy('created_at', 'desc')
    ->get(); 
    
        return PostsResource::collection($posts);
        
    }
     public function Delete_Post($id) {
        try {
            $post = Post::findOrFail($id);
            if($post->files !=null){
                foreach($post->files as $file_url) {
           $public_file_url = public_path($file_url);
        if(File::exists(path: $public_file_url)){

          File::delete($public_file_url);
        }

                }
     
            }
        // set null in DB after delete
        $post->delete();
      
            return $this->JsonResponse("Post Deleted Successfully",200);
        }
          catch (\Exception $e) {
            return response()->json([
                'message' => 'Error while updating Data',
                'error' => $e->getMessage(),
                'line'=> $e->getLine(),
            ], 500);
        }
    }
    public function Update_Post(Request $request) {
        $validation = Validator::make($request->all(),[
            'description' =>'string',
        ]);
        if($validation->fails()){
            return $this->JsonResponse($validation->errors(),422);
        }
        $post = Post::find($request->id);
     //got the array before editing
        $new_files_array = $post->files;
        //looping on the images which i want to delete
            foreach($request->input('files') as $file){
             $image_url = public_path($file);
            
        if(File::exists($image_url)){
          File::delete($image_url);
          $key = array_search($file,$new_files_array);
          if ($key !== false) {
          unset($new_files_array[$key]);
          }
    }
          
  
}
    $post->files = $new_files_array;
    //check if the user edit the bio
    if($request->has('description')){
        //update the bio
        $post->description = $request->description;
    }

    $post->save();
    return $this->JsonResponse("updated successfully",202);
    }
  
}

