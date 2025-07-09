<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable=['name','logo_url'];

    //Skill has many FreelancerPosts
    public function freelancerPosts()
    {
        return $this->hasMany(FreelancerPost::class, 'skill_id');
    }
}
