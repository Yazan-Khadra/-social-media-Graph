<?php

namespace App\Http\Controllers;
use App\JsonResponseTrait;
use App\Models\Group;
use App\Models\User;
use App\Models\Year;
use App\Models\GroupInvitation;
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
            'year_id' => 'required|exists:years,id'
        ]);

        if ($validator->fails()) {
            return $this->JsonResponse($validator->errors(), 400);
        }

        $user = Auth::user();
        //check if the user is already a member of a group
        if ($user->group_id !== null) {
            return $this->JsonResponse("You are already a member of group " . $user->group->group_name . ". Leave your current group first.", 400);
        }

        //create the group
        $group = Group::create([
            'group_name' => $request->group_name,
            'admin_id' => $user->id,
            'year_id' => $request->year_id
        ]);

        //add the group to the user
        $user->group_id = $group->id;
        $user->save();

        return $this->JsonResponse("Group created successfully",201);
    }

    public function addMember(Request $request, $groupId)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return $this->JsonResponse($validator->errors(), 400);
        }

        // Find the group and check if it exists
        $group = Group::findOrFail($groupId);

        // Check if the user is the admin of the group
        if ($group->admin_id !== Auth::id()) {
            return $this->JsonResponse("Only the group admin can send invitations", 403);
        }

        // Check if the group has reached its maximum member limit
        if ($group->members()->count() >= 6) {
            return $this->JsonResponse("Group has reached its maximum member limit of 6", 400);
        }

        // Find the user to invite
        $userToInvite = User::findOrFail($request->user_id);

        // Check if the user is already a member of any group
        if ($userToInvite->group_id !== null) {
            return $this->JsonResponse("User is already a member of group " . $userToInvite->group->group_name, 400);
        }

        // Check if there's already a pending invitation
        if ($group->invitations()->where('user_id', $userToInvite->id)->where('status', 'pending')->exists()) {
            return $this->JsonResponse("An invitation has already been sent to this user", 400);
        }

        // Create the invitation
        $group->invitations()->create([
            'user_id' => $userToInvite->id,
            'status' => 'pending'
        ]);

        return $this->JsonResponse("Invitation sent successfully", 201);
    }

    public function respondToInvitation(Request $request, $invitationId)
    {
        $validator = Validator::make($request->all(), [
            'response' => 'required|in:accept,reject'
        ]);

        if ($validator->fails()) {
            return $this->JsonResponse($validator->errors(), 400);
        }

        $invitation = GroupInvitation::findOrFail($invitationId);

        if ($request->response === 'accept') {
            // Check if the group still has space
            if ($invitation->group->members()->count() >= 6) {
                return $this->JsonResponse("Group has reached its maximum member limit", 400);
            }

            // Add user to the group
            $user = Auth::user();
            $user->group_id = $invitation->group_id;
            $user->save();

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

    public function getPendingInvitations()
    {
        $invitations = GroupInvitation::with('group')
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->get();

        return $this->JsonResponse($invitations, 200);
    }

    public function deleteGroup($groupId)
    {
        // Find the group first
        $group = Group::find($groupId);

        // Check if group exists
        if (!$group) {
            return $this->JsonResponse("Group not found", 404);
        }

        // Check if the authenticated user is the admin of this group
        if ($group->admin_id !== Auth::id()) {
            return $this->JsonResponse("Only the group admin can delete the group", 403);
        }

        // Delete the group
        $group->delete();
        return $this->JsonResponse("Group deleted successfully", 200);
    }

    public function getAllGroups()
    {
        $groups = Group::select('id', 'group_name', 'year_id')->get();
        return $this->JsonResponse($groups, 200);
    }
}
