<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class AcademicStaff extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'profile_image',
        'bio',
        'user_id'
    ];

    protected $casts = [
        "links" => "array"
    ];


}
