<?php

use App\Http\Controllers\FollowController;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\YearController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
<<<<<<< HEAD
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\FreelancerPostController;
=======
use App\Http\Controllers\StudentController;
>>>>>>> 3c218e10693593154f67116bec07cc453f936373
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(JWTAuthController::class)->group(function() {
    Route::post('/user/credintials','Register_Auth');
    Route::post('/user/login','login');
<<<<<<< HEAD
    Route::middleware('Token')->group(function() {
        Route::post('Register','register');
        Route::post('/company/register','company_register');
    });
=======
   
>>>>>>> 3c218e10693593154f67116bec07cc453f936373

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



    });

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
    Route::middleware("Token")->group(function() {
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

    });
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
