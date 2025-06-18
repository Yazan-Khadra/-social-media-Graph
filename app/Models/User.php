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

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile_number',
        'password',
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
        'groups'
    ];

    protected $casts = [
        "social_links" => "array",
        "links" => "array",
        "group_id" => "array"
    ];



    //each student belong to One year

    public function year(): BelongsTo
    {
        return $this->belongsTo(Year::class, 'year_id', 'id');
    }
    //each student belong to One major

    public function major():BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

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


    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }


    public function adminGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'admin_id');
    }

    public function groupStudentProjects()
    {
        return $this->hasMany(GroupStudentProject::class, 'student_id');
    }

}
