<?php

use App\Http\Controllers\CommentController;
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
use App\Http\Controllers\GroupApllayController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\StudentController;
use App\Models\GroupApllay;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(JWTAuthController::class)->group(function() {
    Route::post('/user/credintials','Register_Auth');
    Route::post('/user/login','login');
    Route::middleware('Token')->group(function() {
        Route::post('Register','register');
        Route::post('/role/set','set_role');
        Route::post('/company/register','company_register');
    });
   

}
);
Route::controller(ProjectController::class)->group(function(){
    Route::get('projects_list','show_projects');
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

    });
    Route::get('posts/all/{id}','Get_Posts');
     Route::put('post/update','Update_Post');


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
    Route::get('/freelancer-posts/{id}', 'show_post');

    Route::middleware(['Token', 'Company'])->group(function() {
        Route::post('/freelancer-posts/add', 'add_post');
        Route::put('/freelancer-posts/update/{id}', 'update_post');
        Route::delete('/freelancer-posts/delete/{id}', 'delete_post');
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