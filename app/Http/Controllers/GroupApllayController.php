<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupApplayResource;
use App\JsonResponseTrait;
use App\Models\Group;
use App\Models\GroupApllay;
use App\Models\GroupStudentProject;
use Exception;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\skiil_group_pivot;

class GroupApllayController extends Controller
{
    use JsonResponseTrait;
    public function Applay_to_Group(Request $request) {
        $validation = Validator::make($request->all(),[
            "group_id" => "required",
            "skill_id" => "required"
        ]);
        if($validation->fails()) {
            return $this->JsonResponse($validation->errors(),422);
        }
         // Check if the group has reached its maximum member limit for this project
        $members_of_group = GroupStudentProject::where('group_id', $request->group_id)
            ->where('project_id', $request->project_id)
            ->count();

        if ($members_of_group >= 6) {
            return $this->JsonResponse("Group has reached its maximum member limit for this project", 400);
        }
         // Check if the user is already a member of any group for this project
        $alreadyMember = GroupStudentProject::where('student_id', Auth::user()->id)
            ->where('project_id', $request->project_id)
            ->exists();
        if ($alreadyMember) {
            return $this->JsonResponse("User is already a member of a group for this project", 400);
        }
        // Check if there's already a pending invitation for this user, group, and project
        $pendingInvitation = GroupApllay::where('group_id', $request->group_id)
            ->where('student_id', Auth::user()->id)
            ->where('status', 'pending')
            ->exists();
        if ($pendingInvitation) {
            return $this->JsonResponse("An invitation has already been sent to this user for this project", 400);
        }
        GroupApllay::create([
            "student_id" =>Auth::user()->id,
            "group_id" => $request->group_id,
            "skill_id" => $request->skill_id,
            "post_id" => $request->post_id
        ]);
        return $this->JsonResponse("Applay done successfully",200);
    
    }
        public function Response_To_Applay_Request(Request $request)
    {
        try{
        $validator = Validator::make($request->all(), [
            'response' => 'required|in:accept,reject'
        ]);

        if ($validator->fails()) {
            return $this->JsonResponse($validator->errors(), 422);
        }

        $invitation = GroupApllay::findOrFail($request->Applay_id);
        $project_id = $invitation->Group->project_id;
       

        if ($request->response === 'accept') {
            // Check if the group still has space
            // if ($invitation->group->members->count() >= 6) {
            //     return $this->JsonResponse("Group has reached its maximum member limit", 400);
            // }
            // Add user to the group
            
            $deleted = skiil_group_pivot::where('group_id',$invitation->post_id)
            ->where('skill_id',$request->skill_id)->delete();
            
            

            GroupStudentProject::create([
                'student_id' => $request->student_id,
                'project_id' => $project_id,
                'group_id' => $invitation->group_id,
                'skill_id' => $request->skill_id,
            ]);
            
            
            // Update invitation status
            $invitation->status = 'accepted';
            $invitation->save();

            return $this->JsonResponse("You have joined the group successfully", 200);
        } else {
            // Update invitation status
            $invitation->status = 'rejected';
            $invitation->save();

            return $this->JsonResponse("Invitation rejected", 200);
        }
    }
    catch(Exception $e) {
        $error = [
            'message' =>$e->getMessage(),
            'line'=> $e->getLine(),
        ];
        return $this->JsonResponse($error,422);
    }
    }
    public function Get_Applay_Requests($group_id) {
    
        $applaies = GroupApllay::where('group_id',$group_id)
        ->where("status","pending")
        ->get();
        return GroupApplayResource::collection($applaies);
// input("will press accept" );
// print("deletethe post");

    }
}
