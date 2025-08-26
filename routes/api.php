<?php

use App\Http\Controllers\AcademicStaffController;
use App\Http\Controllers\AcademicStaffPostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\CommentResponsesController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\GroupPostController;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\YearController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\FreelancerPostController;
use App\Http\Controllers\FreelancerApplicationController;
use App\Http\Controllers\GroupApllayController;
use App\Http\Controllers\IdentityController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TechnicalSupportController;
use App\Models\AcademicStaff;
use App\Models\EmailOtp;
use App\Models\GroupApllay;
use App\Models\Reaction;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(JWTAuthController::class)->group(function() {
    Route::post('/user/credintials','Register_Auth');
    Route::post('/user/login','login');
    Route::middleware('Token')->group(function() {
        Route::post('Register','register');
        Route::post('/role/set','set_role');
        Route::post('/company/register','company_register');
        Route::get('/logout','logout');
    });


}
);
// edit password of an account
Route::middleware('Token')->group(function() {
    Route::post('user/change-password', [PasswordController::class, 'changePassword']);
});

Route::controller(UserController::class)->group(function() {

Route::get('get/user_profile/info','Side_info')->middleware('Token');

});
Route::controller(ProjectController::class)->group(function(){
    Route::get('projects_list','show_projects');
    Route::get('get_all_projects','Get_All');
    Route::post('add_project','add_project');
    Route::delete('delete_project/{project_id}','delete_project');

});
Route::controller(StudentController::class)->group(function() {
    Route::middleware("Token")->group(function() {
        //register user informations
         Route::middleware('Student')->group(function() {
        Route::post('Register','register');
    });
        //get user informations

        Route::get('/user/info',"Get_User_Profile_Info");


        //fill the user informatons
        Route::post("/fill/user/info","Fill_Profile_Info");
        // add social links
        Route::post('/student/social_links/add',"Set_Social_Links");
        // update social links route
        Route::put('/student/social_links/update',"Update_Social_Links");
        // delete social_links
        Route::Delete('/delete/student/social_link',"Delete_social_link");
        // update bio
        Route::put('/student/bio/update','Update_Bio');
        // update profile photo
        Route::post('/student/prfile-photo/update','Update_Profile_Image');
        // delete profile image
        Route::delete('/student/profile-image/delete', "Delete_Profile_Image");
        //get users post
        Route::get('/user/posts/{id}','Get_User_Post');
        // find student




    });
     Route::get('/user/info/{id}',"Get_User_Profile_Info");
     Route::post('student/find','search');

});
Route::controller(SkillController::class)->group(function(){
    Route::get('skills_list','show_skill');
    Route::get('user_skill','show_user_skill')->middleware('Token');
    Route::post('select_skill','choose_skill')->middleware('Token');
    Route::delete('delete_skill/{id}','delete_skill')->middleware('Token');
});
Route::controller(YearController::class)->group(function(){
    Route::get('year_list', 'show_all_years');
    Route::get('student_by_year/{id}', 'getStudentsByYear');

});
Route::controller(MajorController::class)->group(function(){
    Route::get('major_list','show_all_majors');
    Route::get('student_by_major/{id}','show_student_by_major');
});
Route::middleware("Token")->group(function () {
    Route::post('/follow/{id}', [FollowController::class, 'follow']);
    Route::post('/unfollow/{id}', [FollowController::class, 'unfollow']);
    Route::get('/followers', [FollowController::class, 'followers']);
    Route::get('/followings', [FollowController::class, 'followings']);
});

Route::controller(GroupController::class)->group(function() {
    Route::middleware(["Token","Student"])->group(function() {
        // Create group
        Route::post('/groups_Create', 'createGroup');
        // Get all groups
        Route::get('/groups', 'getAllGroups');
        // Send invitation to join group
        Route::post('/groups/invite', 'addMember');
        // Get pending invitations
        Route::get('/invitations', 'getPendingInvitations');
        // Respond to invitation
        Route::post('/invitations/{invitationId}', 'respondToInvitation');
        // Delete group
        Route::delete('/groups/{groupId}', 'deleteGroup');
        //get group's members
        Route::get('/group/member/{id}','GetGroupMember');
        //leave group
        Route::get('group/leave/{group_id}', 'Leave_Group');
        



    });
    Route::get('/groupsByProject', 'get_groups_by_project');
});
Route::controller(GroupApllayController::class)->group(function() {
    Route::middleware(["Token","Student"])->group(function() {
        Route::post("group/applay","Applay_to_Group");
    });
    Route::post("Applay/response",'Response_To_Applay_Request');
    Route::get("Applay/get/{group_id}","Get_Applay_Requests");

});

Route::controller(CompanyController::class)->group(function() {
    // Public routes (no authentication required)
    Route::get('/companies', 'show_all_company');
    Route::get('/companies/search', 'search');
    Route::get('/companies/{id}', 'show_one_company');

    Route::middleware(['Token', 'Company'])->group(function() {
        Route::post('/companies/fill-info', 'Fill_Company_Info');
        Route::post('/companies/social-links/add', 'Set_Social_Links');
        Route::put('/companies/social-links/update', 'Update_Social_Links');
        Route::put('/companies/update-info', 'update_company_info');
        Route::post('/companies/update-logo', 'update_logo_url');
        Route::delete('/companies/delete-logo', 'delete_logo_url');
        Route::delete('/companies/delete-account', 'destroy');
    });
});

Route::controller(PostController::class)->group(function() {
    Route::middleware('Token')->group(function() {
        Route::post('posts/make','Create_Post');
        Route::delete('post/delete/{id}','Delete_Post');
        Route::put('post/update','Update_Post');
        Route::post('search/Hashtags','searchHashtags');
    });
    Route::get('posts/all/{id}','Get_Posts');
    Route::get('posts/hashtag/{hashtag}', [PostController::class, 'getPostsByHashtag']);


});
Route::controller(GroupPostController::class)->group(function() {
    Route::middleware('Student')->group(function() {
        Route::post('group/post/create','Create_Post');
    });
    Route::get('group/posts/get','Get_Groups_Posts');
    Route::delete("post/delete/{post_id}","Delete_Post");

});

Route::controller(FreelancerPostController::class)->group(function() {
    // Public routes (no authentication required)
    Route::get('/freelancer-posts', 'get_all_posts');
    Route::get('/freelancer-posts/{id}', [FreelancerPostController::class, 'get_post_of_company']);
    //freelancer posts
    Route::middleware(['Token', 'Company'])->group(function() {
        Route::post('/freelancer-posts/add', 'add_post');
        Route::put('/freelancer-posts_update/{id}',[FreelancerPostController::class, 'update_post']);
        Route::delete('/freelancer-posts/delete/{id}', 'delete_post');
    });
    // freelancer application
Route::middleware('student')->group(function () {

    Route::post('/freelancer-applications', [FreelancerApplicationController::class, 'send_application']);
    Route::get('/my-applications', [FreelancerApplicationController::class, 'my_applications']);
});



});
 Route::controller(CommentController::class)->group(function() {
        Route::middleware('Token')->group(function() {
            Route::post('/comment/make','make_comment');
        });
        Route::get('/post/comment/get/{post_id}','Get_Post_comments');
        Route::delete('comment/delete/{comment_id}','Delete_Comment');
        Route::post('comment/update','Update_Comment');
    });
    Route::controller(CommentResponsesController::class)->group(function() {
        Route::middleware('Token')->group(function() {
            Route::post('comment/response','Response_To_Comment');
        });
        Route::delete('commentResponse/delete/{response_id}','Delete_Response');
        Route::post('comment/update','Update_Response');
    });
Route::controller(ReactionController::class)->group(function() {
    Route::middleware('Token')->group(function() {
        Route::post('reaction/make','Make_Reaction');
        Route::post('reaction/update','Update_Reaction');
        Route::delete('reaction/delete/{post_id}','Remove_Reaction');
        Route::get('reaction/get/{post_id}','Get_Post_reactions');
    });
});

// Notification routes
Route::controller(NotificationController::class)->group(function() {
    Route::middleware('Token')->group(function() {
        Route::post('notifications/update-fcm-token', 'updateFCMToken');
    });
});




Route::middleware('Token')->post('/email/verify-otp', function (Request $request) {
    // تحقق من صحة الإدخال
    $request->validate([
        'otp' => 'required|string',
    ]);

    $user = $request->user();

    // البحث عن OTP صالح
    $otpRecord = EmailOtp::where('user_id', $user->id)
        ->where('otp', $request->otp)
        ->where('expires_at', '>=', Carbon::now())
        ->first();

    if (!$otpRecord) {
        return response()->json(['message' => 'Invalid or expired verification code'], 422);
    }

    // تحديث حالة المستخدم
    $user->email_verified_at = now();
    $user->save();

    // حذف الكود بعد الاستخدام
    $otpRecord->delete();

    return response()->json(['message' => 'Verified successfully!']);
})->name('email.verify.otp');


    Route::controller(AcademicStaffController::class)->group(function() {

        Route::middleware(['Token','staff'])->group(function() {
            //academic staff register
            Route::post('staff/register','Register_Staff_Member');
            Route::post('staff/updateInfo','update_academic_staff_info');
            Route::post('staff/updateImage','update_logo_url');
            Route::delete('staff/deleteImage','delete_logo_url');
            Route::delete('staff/deleteaccount','destroy');
        });
        Route::get('/show_staff/{id}', [AcademicStaffController::class, 'show_one_academic_staff']);
        Route::get('/show_all_staff','show_all_academic_staff');
        Route::get('/search_academy','search');
    });
        // academy post
        Route::controller(AcademicStaffPostController::class)->group(function() {

        Route::get('academic-posts','get_all_posts');
        Route::get('academic-posts/{id}',[AcademicStaffPostController::class, 'get_posts_of_academic_staff']);
        Route::middleware(['Token','staff'])->group(function() {
            Route::post('create_post','add_post');
            Route::post('update_post/{id}','update_post');
            Route::delete('delete_post/{id}','delete_post');


        });


    });

Route::middleware('Token')->group(function () {
    Route::post('/identity/set-info', [IdentityController::class, 'Set_info']);


});
// indentity
Route::get('/identity/pending-orders', [IdentityController::class, 'Get_Pending_orders']);
Route::post('/identity/response', [IdentityController::class, 'Response_to_order']);
//technical support
Route::post('/technical-support/issue', [TechnicalSupportController::class, 'Set_issue']);

// Get all pending issues
Route::get('/technical-support/issues', [TechnicalSupportController::class, 'Get_issues']);

// Respond to an issue (send email)
Route::post('/technical-support/respond/{id}', [TechnicalSupportController::class, 'respondToIssue']);

