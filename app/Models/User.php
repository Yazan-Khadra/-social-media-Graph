<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\GroupStudentProject;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'mobile_number',
        'password',
<<<<<<< HEAD
        'profile_image',
        'gender',
        'birth_date',
        'year_id',
        'major_id',
        'bio',
        'cv_url',
        'profile_image_url',
        'social_links',
        'rate',
        'skills',
        'group_id',
        'groups',
        'role'
=======
>>>>>>> 3c218e10693593154f67116bec07cc453f936373
    ];


    //each student belong to One year

 

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }


    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_user_id', 'user_id');
    }

public function followings()
{
    return $this->belongsToMany(User::class, 'follows', 'user_id', 'followed_user_id');
}
<<<<<<< HEAD
// user's post
public function Posts() {
    return $this->belongsToMany(Post::class,"posts_users_pivot","user_id");
}

=======
// user's post 
// public function Posts() {
//     return $this->belongsToMany(Post::class,"posts_users_pivot","user_id");
// }
    
>>>>>>> 3c218e10693593154f67116bec07cc453f936373


//     public function groups(): BelongsToMany
//     {
//         return $this->belongsToMany(Group::class,'group_student_project','student_id')->withPivot('is_admin');
//     }


//     public function adminGroups(): HasMany
//     {
//         return $this->hasMany(Group::class, 'admin_id');
//     }

//     public function groupStudentProjects()
//     {
//         return $this->hasMany(GroupStudentProject::class, 'student_id');
//     }
    public function Student()  {
        return $this->hasOne(Student::class,'user_id');
    }
<<<<<<< HEAD


    public function adminGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'admin_id');
    }

    public function groupStudentProjects()
    {
        return $this->hasMany(GroupStudentProject::class, 'student_id');
    }



=======
>>>>>>> 3c218e10693593154f67116bec07cc453f936373
}
