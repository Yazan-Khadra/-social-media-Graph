<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class AcademicStaff extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'profile_image',
        'gender',
        'birth_date',
        'bio',
        'social_links',
        'user_id'
    ];

    protected $casts = [
        "social_links" => "array",
        "links" => "array",
    ];


}
