<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Freelancer_application extends Model
{
    protected $fillable = [
        'student_id',
        'freelance_post_id'
    ];

    public function post()
    {
        return $this->belongsTo(FreelancerPost::class, 'freelance_post_id');
    }
}
// get all post of company and application
// user()->post()->application();
