<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Skill;
use App\Models\User;
use App\JsonResponseTrait;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class SkillController extends Controller
{
    use JsonResponseTrait;
    //show the skills list
   public function show_skill(){

        $skill=Skill::all();
            return $this->JsonResponseWithData("this is the skill list",$skill,200);
   }
    public function show_user_skill(Request $request){
        $user_skill=Student::where('id',Auth::user()->id)->get()->first();
        
        return $this->JsonResponseWithData("this is the user skill list",$user_skill->skills,200);
    }


    public function choose_skill(Request $request){

        $validation=Validator::make($request->all(),[
            'choice_id'=>'required|array'
        ]);

        if($validation->fails()){
            return $this->JsonResponse($validation->errors(),400);
        }

        $selectedSkills = [];
        foreach ($request->choice_id as $id) {
            $skill = Skill::find($id);
            if ($skill) {
                $selectedSkills[] = [
                    'id' => $skill->id,
                    'name' => $skill->name,
                    'logo_url'=>$skill->logo_url,
                ];
            }
        }

        // Get the authenticated user
        $user = Student::findOrFail(Auth::user()->id);
        if (!$user) {
            return $this->JsonResponse('User not authenticated', 401);
        }

        // Save the selected skills to the user's account
        $user->skills = $selectedSkills;
        $user->save();

        return $this->JsonResponseWithData('Your skills have been saved successfully', $selectedSkills, 200);
    }

   //delete a skill
   public function delete_skill(Request $request){
        $validation = Validator::make($request->all(),[
            "skills" => "required|array",
        ]);
        if($validation->fails()){
            return $this->JsonResponse($validation->errors(),422);
        }
        //get the user
        $user = Student::findOrFail(Auth::user()->id);
        $user_skills = $user->skills ?? [];

        // Ensure we're working with an array
        if (!is_array($user_skills)) {
            $user_skills = json_decode($user_skills, true) ?? [];
        }

        foreach($request->skills as $deleted_skill){
            //use array filter higher order function to delete filter the array
            $new_arraySkills = array_filter($user_skills, function($skill) use ($deleted_skill) {
                return $skill['id'] != $deleted_skill['id'];
            });
        }
        $user->skills = $new_arraySkills;
        $user->save();
        $response = [
            "message" => "data deleted successfully"
        ];
        return $this->JsonResponse($response,200);
    }
}

