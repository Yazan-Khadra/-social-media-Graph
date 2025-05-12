<?php


use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\SkillController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(JWTAuthController::class)->group(function() {
    Route::post('Register','register');
    Route::middleware('Token')->group(function() {
    });
}
);
Route::controller(SkillController::class)->group(function(){
    Route::get('skills_list','show_skill');
    Route::post('select_skill','choose_skill');
    Route::delete('delete_skill','delete_skill');
});

