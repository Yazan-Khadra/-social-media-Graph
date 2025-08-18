<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Company;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Reaction;
use App\Models\GroupApllay;
use App\Models\Freelancer_application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    protected $fcmServerKey;
    protected $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

    public function __construct()
    {
        $this->fcmServerKey = env('FCM_SERVER_KEY');
    }

    // Notify group admin when someone applies to group
    public function notifyGroupApplication($groupId, $applicantId)
    {
        try {
            $group = Group::with('admin')->findOrFail($groupId);
            $applicant = User::with('Student')->findOrFail($applicantId);

            $adminUser = $group->admin;
            $fcmToken = $adminUser->fcm_token; // You'll need to add this field to users table

            if (!$fcmToken) {
                Log::info('No FCM token for user: ' . $adminUser->id);
                return;
            }

            $notification = [
                'title' => 'New Group Application',
                'body' => $applicant->Student->first_name . ' ' . $applicant->Student->last_name . ' applied to join ' . $group->group_name,
                'data' => [
                    'type' => 'group_application',
                    'group_id' => $groupId,
                    'group_name' => $group->group_name,
                    'applicant_id' => $applicantId,
                    'applicant_name' => $applicant->Student->first_name . ' ' . $applicant->Student->last_name,
                    'click_action' => 'GROUP_APPLICATION'
                ]
            ];

            $this->sendFCMNotification($fcmToken, $notification);

            Log::info('Group application notification sent to admin: ' . $adminUser->id);

        } catch (\Exception $e) {
            Log::error('Error sending group application notification: ' . $e->getMessage());
        }
    }

    // Notify company when someone applies to freelancer post
    public function notifyFreelancerApplication($applicationId)
    {
        try {
            $application = Freelancer_application::with(['freelancerPost.company.user'])->findOrFail($applicationId);
            $companyUser = $application->freelancerPost->company->user;
            $applicant = User::with('Student')->findOrFail($application->student_id);

            $fcmToken = $companyUser->fcm_token;

            if (!$fcmToken) {
                Log::info('No FCM token for company user: ' . $companyUser->id);
                return;
            }

            $notification = [
                'title' => 'New Freelancer Application',
                'body' => $applicant->Student->first_name . ' ' . $applicant->Student->last_name . ' applied to your freelancer post',
                'data' => [
                    'type' => 'freelancer_application',
                    'application_id' => $applicationId,
                    'post_id' => $application->freelance_post_id,
                    'post_title' => $application->freelancerPost->title,
                    'applicant_name' => $applicant->Student->first_name . ' ' . $applicant->Student->last_name,
                    'click_action' => 'FREELANCER_APPLICATION'
                ]
            ];

            $this->sendFCMNotification($fcmToken, $notification);

            Log::info('Freelancer application notification sent to company: ' . $companyUser->id);

        } catch (\Exception $e) {
            Log::error('Error sending freelancer application notification: ' . $e->getMessage());
        }
    }

    // 3. Notify user when someone comments on their post
    public function notifyPostComment($commentId)
    {
        try {
            $comment = Comment::with(['post.Students.user'])->findOrFail($commentId);
            $commenter = User::with('Student')->findOrFail($comment->user_id);

            // Get all users who own this post
            $postOwners = $comment->post->Students;

            foreach ($postOwners as $postOwner) {
                $ownerUser = $postOwner->user;

                // Don't notify the commenter themselves
                if ($ownerUser->id === $comment->user_id) {
                    continue;
                }

                $fcmToken = $ownerUser->fcm_token;

                if (!$fcmToken) {
                    continue;
                }

                $notification = [
                    'title' => 'New Comment on Your Post',
                    'body' => $commenter->Student->first_name . ' commented: ' . substr($comment->content, 0, 50) . '...',
                    'data' => [
                        'type' => 'post_comment',
                        'post_id' => $comment->post_id,
                        'post_title' => $comment->post->title ?? 'Your Post',
                        'comment_id' => $commentId,
                        'commenter_id' => $comment->user_id,
                        'commenter_name' => $commenter->Student->first_name . ' ' . $commenter->Student->last_name,
                        'click_action' => 'POST_COMMENT'
                    ]
                ];

                $this->sendFCMNotification($fcmToken, $notification);
            }

            Log::info('Post comment notification sent for comment: ' . $commentId);

        } catch (\Exception $e) {
            Log::error('Error sending post comment notification: ' . $e->getMessage());
        }
    }

    // 4. Notify user when someone reacts to their post
    public function notifyPostReaction($reactionId)
    {
        try {
            $reaction = Reaction::with(['posts.Students.user'])->findOrFail($reactionId);
            $reactor = User::with('Student')->findOrFail($reaction->user_id);

            // Get all users who own this post
            $postOwners = $reaction->posts;

            foreach ($postOwners as $postOwner) {
                $ownerUser = $postOwner->user;

                // Don't notify the reactor themselves
                if ($ownerUser->id === $reaction->user_id) {
                    continue;
                }

                $fcmToken = $ownerUser->fcm_token;

                if (!$fcmToken) {
                    continue;
                }

                $notification = [
                    'title' => 'New Reaction on Your Post',
                    'body' => $reactor->Student->first_name . ' reacted to your post',
                    'data' => [
                        'type' => 'post_reaction',
                        'post_id' => $reaction->post_id,
                        'post_title' => $reaction->posts->first()->title ?? 'Your Post',
                        'reaction_id' => $reactionId,
                        'reactor_id' => $reaction->user_id,
                        'reactor_name' => $reactor->Student->first_name . ' ' . $reactor->Student->last_name,
                        'click_action' => 'POST_REACTION'
                    ]
                ];

                $this->sendFCMNotification($fcmToken, $notification);
            }

            Log::info('Post reaction notification sent for reaction: ' . $reactionId);

        } catch (\Exception $e) {
            Log::error('Error sending post reaction notification: ' . $e->getMessage());
        }
    }

    // 5. Notify student when group application is accepted/rejected
    public function notifyGroupApplicationResponse($applicationId, $status)
    {
        try {
            $application = GroupApllay::with(['student.user', 'group'])->findOrFail($applicationId);
            $studentUser = $application->student->user;

            $fcmToken = $studentUser->fcm_token;

            if (!$fcmToken) {
                Log::info('No FCM token for student: ' . $studentUser->id);
                return;
            }

            $statusText = $status === 'accepted' ? 'accepted' : 'rejected';

            $notification = [
                'title' => 'Group Application ' . ucfirst($statusText),
                'body' => 'Your application to join ' . $application->group->group_name . ' has been ' . $statusText,
                'data' => [
                    'type' => 'group_application_response',
                    'group_id' => $application->group_id,
                    'group_name' => $application->group->group_name,
                    'application_id' => $applicationId,
                    'status' => $status,
                    'click_action' => 'GROUP_APPLICATION_RESPONSE'
                ]
            ];

            $this->sendFCMNotification($fcmToken, $notification);

            Log::info('Group application response notification sent to student: ' . $studentUser->id);

        } catch (\Exception $e) {
            Log::error('Error sending group application response notification: ' . $e->getMessage());
        }
    }

    // 6. Notify student when freelancer application is accepted/rejected
    public function notifyFreelancerApplicationResponse($applicationId, $status)
    {
        try {
            $application = Freelancer_application::with(['student.user', 'freelancerPost.company'])->findOrFail($applicationId);
            $studentUser = $application->student->user;

            $fcmToken = $studentUser->fcm_token;

            if (!$fcmToken) {
                Log::info('No FCM token for student: ' . $studentUser->id);
                return;
            }

            $statusText = $status === 'accepted' ? 'accepted' : 'rejected';

            $notification = [
                'title' => 'Freelancer Application ' . ucfirst($statusText),
                'body' => 'Your application to ' . $application->freelancerPost->company->company_name . ' has been ' . $statusText,
                'data' => [
                    'type' => 'freelancer_application_response',
                    'application_id' => $applicationId,
                    'post_id' => $application->freelance_post_id,
                    'post_title' => $application->freelancerPost->title,
                    'company_name' => $application->freelancerPost->company->company_name,
                    'status' => $status,
                    'click_action' => 'FREELANCER_APPLICATION_RESPONSE'
                ]
            ];

            $this->sendFCMNotification($fcmToken, $notification);

            Log::info('Freelancer application response notification sent to student: ' . $studentUser->id);

        } catch (\Exception $e) {
            Log::error('Error sending freelancer application response notification: ' . $e->getMessage());
        }
    }

    // Send FCM notification
    private function sendFCMNotification($fcmToken, $notification)
    {
        try {
            $data = [
                'to' => $fcmToken,
                'notification' => [
                    'title' => $notification['title'],
                    'body' => $notification['body'],
                    'sound' => 'default',
                    'badge' => 1
                ],
                'data' => $notification['data'],
                'priority' => 'high'
            ];

            $response = Http::withHeaders([
                'Authorization' => 'key=' . $this->fcmServerKey,
                'Content-Type' => 'application/json'
            ])->post($this->fcmUrl, $data);

            if ($response->successful()) {
                Log::info('FCM notification sent successfully');
            } else {
                Log::error('FCM notification failed: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Error sending FCM notification: ' . $e->getMessage());
        }
    }

    // Update user's FCM token
    public function updateFCMToken(Request $request)
    {
        try {
            $request->validate([
                'fcm_token' => 'required|string'
            ]);

            $user = auth()->user();
            $user->update(['fcm_token' => $request->fcm_token]);

            return response()->json(['message' => 'FCM token updated successfully']);

        } catch (\Exception $e) {
            Log::error('Error updating FCM token: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update FCM token'], 500);
        }
    }
}
