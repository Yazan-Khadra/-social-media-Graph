<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\GroupStudentProject;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
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
        'groups',
        'user_id'
    ];

    protected $casts = [
        "social_links" => "array",
        "links" => "array",
        "group_id" => "array",
        "skills" => "array"
    ];
public function year(): BelongsTo
    {
        return $this->belongsTo(Year::class, 'year_id', 'id');
    }
    //each student belong to One major

    public function major():BelongsTo
    {
        return $this->belongsTo(Major::class);
    }
    public function Posts() {
    return $this->belongsToMany(Post::class,"_posts__users__pivot","user_id",'post_id');
}
    


    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class,'group_student_project','student_id')->withPivot(['is_admin','skill_id']);
    }


    public function adminGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'admin_id');
    }

    public function groupStudentProjects()
    {
        return $this->hasMany(GroupStudentProject::class, 'student_id');
    }
    public function User() {
        return $this->belongsTo(User::class);
    }

}
