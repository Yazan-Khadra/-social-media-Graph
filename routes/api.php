<?php


use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(JWTAuthController::class)->group(function() {
    Route::post('Register','register');
    Route::post('/user/login','login');
    
}
);
Route::controller(UserController::class)->group(function() {
    Route::middleware("Token")->group(function() {
        //get user informations
        Route::get('/user/info',"Get_User_Profile_Info");
        //fill the user informatons
        Route::post("/fill/user/info","Fill_Profile_Info");
        // update social links route
        Route::post('/student/social_links/update',"Update_Social_Links");
        // delete social_links
        Route::Delete('/delete/student/social_link',"Delete_social_link");
    });
    
});
Route::controller(SkillController::class)->group(function(){
    Route::get('skills_list','show_skill');
    Route::post('select_skill','choose_skill');
    Route::delete('delete_skill','delete_skill');
});

