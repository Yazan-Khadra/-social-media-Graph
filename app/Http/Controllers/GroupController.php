<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupInformationResource;
use App\Http\Resources\GroupMembersResource;
use App\Http\Resources\PendingInvitationResource;
use App\JsonResponseTrait;
use App\Models\Group;
use App\Models\User;
use App\Models\Year;
use App\Models\GroupInvitation;
use App\Models\GroupStudentProject;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class GroupController extends Controller
{
    use JsonResponseTrait;

    public function createGroup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id'
        ]);

        if ($validator->fails()) {
            return $this->JsonResponse($validator->errors(), 400);
        }

        $user = Auth::user();

        // Check if the user is already an admin of a group for this project
        $adminGroup = Group::where('admin_id', $user->id)
            ->where('project_id', $request->project_id)
            ->first();

        if ($adminGroup) {
            return $this->JsonResponse("You are already an admin of group " . $adminGroup->group_name . " for this project.", 400);
        }

        // Create the group
        $group = Group::create([
            'group_name' => $request->group_name,
            'admin_id' => $user->id,
            'project_id' =>$request->project_id
        ]);
        GroupStudentProject::create([
            'student_id'=>Auth::user()->id,
            'group_id'=>$group->id,
            'project_id'=>$request->project_id,
            'is_admin'=>1,
        ]);
        return $this->JsonResponse("Group created successfully", 201);
    }

    public function addMember(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id'
        ]);

        if ($validator->fails()) {
            return $this->JsonResponse($validator->errors(), 400);
        }

        // Find the group and check if it exists
        $group = Group::findOrFail($request->groupId);
        
        
        // Check if the user is the admin of the group for this project
        if ($group->admin_id !== Auth::user()->id || $group->project_id != $request->project_id) {
            return $this->JsonResponse("Only the group admin for this project can send invitations", 403);
        }

        // Check if the group has reached its maximum member limit for this project
        $members_of_group = GroupStudentProject::where('group_id', $request->groupId)
            ->where('project_id', $request->project_id)
            ->count();

        if ($members_of_group >= 6) {
            return $this->JsonResponse("Group has reached its maximum member limit for this project", 400);
        }

        // Check if the user is already a member of any group for this project
        $alreadyMember = GroupStudentProject::where('student_id', $request->user_id)
            ->where('project_id', $request->project_id)
            ->exists();
        if ($alreadyMember) {
            return $this->JsonResponse("User is already a member of a group for this project", 400);
        }

        // Check if there's already a pending invitation for this user, group, and project
        $pendingInvitation = GroupInvitation::where('group_id', $request->groupId)
            ->where('student_id', $request->user_id)
            ->where('status', 'pending')
            ->where('project_id', $request->project_id)
            ->exists();
        if ($pendingInvitation) {
            return $this->JsonResponse("An invitation has already been sent to this user for this project", 400);
        }

        // Create the invitation
        GroupInvitation::create([
            'group_id' => $request->groupId,
            'student_id' => $request->user_id,
            'status' => 'pending',
            'project_id' => $request->project_id
        ]);

        return $this->JsonResponse("Invitation sent successfully", 201);
    }

    public function respondToInvitation(Request $request, $invitationId)
    {
        try{
        $validator = Validator::make($request->all(), [
            'response' => 'required|in:accept,reject'
        ]);

        if ($validator->fails()) {
            return $this->JsonResponse($validator->errors(), 422);
        }

        $invitation = GroupInvitation::findOrFail($invitationId);
        

        if ($request->response === 'accept') {
            // Check if the group still has space
            // if ($invitation->group->members->count() >= 6) {
            //     return $this->JsonResponse("Group has reached its maximum member limit", 400);
            // }

            // Add user to the group
            GroupStudentProject::create([
                'student_id' => Auth::user()->id,
                'project_id' => $invitation->project_id,
                'group_id' => $invitation->group_id
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

    public function getPendingInvitations()
    {
        $invitations = GroupInvitation::with('group')
            ->where('student_id', Auth::id())
            ->where('status', 'pending')
            ->get();
            
          
            

        return PendingInvitationResource::collection($invitations);
    }

    public function deleteGroup(Request $request, $groupId)
    {
        // Find the group first
        $group = Group::find($groupId);

        // Check if group exists
        if (!$group) {
            return $this->JsonResponse("Group not found", 404);
        }

        // Check if the authenticated user is the admin of this group for this project
        if ($group->admin_id !== Auth::id()) {
            return $this->JsonResponse("Only the group admin can delete the group", 403);
        }

        // Delete the group
        $group->delete();
        return $this->JsonResponse("Group deleted successfully", 200);
    }

    public function getAllGroups()
    {
        $user = Student::where('id',Auth::user()->id)->get()->first();
        
        $groups = $user->groups;
        return GroupInformationResource::collection($groups);
    }

public function GetGroupMember($id){
    $group=Group::findOrFail($id);
    $members = $group->members;
    return GroupMembersResource::collection($members);
}

}