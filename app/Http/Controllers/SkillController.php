<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Skill;
use App\Models\User;
use App\JsonResponseTrait;
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
        $user=Auth::user();
        $user_skill=$user->skills;
        return $this->JsonResponseWithData("this is the user skill list",$user_skill,200);
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
        $user = Auth::user();
        if (!$user) {
            return $this->JsonResponse('User not authenticated', 401);
        }

        // Save the selected skills to the user's account
        $user->skills = $selectedSkills;
        $user->save();

        return $this->JsonResponseWithData('Your skills have been saved successfully', $selectedSkills, 200);
    }

   //delete a skill
   public function delete_skill($id){
        // Check authentication
        $user = Auth::user();
        if(!$user) {
            return $this->JsonResponse('User not authenticated', 401);
        }

        // Get skills with null check
        $skills = $user->skills ?? [];

        // Check if user has any skills
        if(empty($skills)) {
            return $this->JsonResponse('No skills found', 404);
        }

        // Find and remove the skill with the matching ID
        $found = false;
        $updatedSkills = [];
        foreach($skills as $skill) {
            if($skill['id'] == $id) {
                $found = true;
                continue; // Skip this skill (remove it)
            }
            $updatedSkills[] = $skill;
        }

        // Save the updated skills
        $user->skills = $updatedSkills;
        $user->save();

        return $this->JsonResponse('Skill deleted successfully', 200);
    }
}

