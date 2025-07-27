<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    // JobType belongs to many FreelancerPosts
    public function freelancerPosts(): BelongsToMany
    {
        return $this->belongsToMany(FreelancerPost::class, 'freelancer_post_job_type');
    }
}
