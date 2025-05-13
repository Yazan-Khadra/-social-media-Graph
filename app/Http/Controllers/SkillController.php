<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Skill;
use App\JsonResponseTrait;
class SkillController extends Controller
{
    use JsonResponseTrait;
    //show the skills list
   public function show_skill(){

        $skill=Skill::all();
            return $this->JsonResponseWithData("this is the skill list",$skill,200);
   }

   public function choose_skill(Request $request){
    $validation=Validator::make($request->all(),[
        'choice_id'=>'required|array'
    ]);
    if($validation->fails()){
        return $this->JsonResponse($validation->errors(),400);
    }
    // $selectedSkills = Skill::whereIn('id', (array) $request->choice_id)->get();
    //  $selectedSkills = [];

    // foreach ($request->choice_id as $id) {
    //     $skill = Skill::find($id);
    //     if ($skill) {
    //         $selectedSkills[] = $skill;
    //     }
    // }
      
    // return $this->JsonResponseWithData('your selection has saved',$selectedSkills,200);
   }

   //delete a skill
   public function delete_skill($id){

    $delete_skill=Skill::get()->where('id',$id);
        if(!$delete_skill){
            return $this->JsonResponse("the skill is not found ",404);
        }
        $delete_skill->delete();
            return $this->JsonResponse('the skill has deleted sucsessfully',200);
   }
}

