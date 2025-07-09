<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FreelancerPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'company_id',
        'skill_id'    ];

    //FreelancerPost belongs to Company
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    //FreelancerPost belongs to many WorkPlaces
    public function workPlaces(): BelongsToMany
    {
        return $this->belongsToMany(WorkPlace::class, 'freelancer_post_work_place');
    }

    //FreelancerPost belongs to many JobTypes
    public function jobTypes(): BelongsToMany
    {
        return $this->belongsToMany(JobType::class, 'freelancer_post_job_type');
    }

    //FreelancerPost has many FreelancerApplications
    public function applications(): HasMany
    {
        return $this->hasMany(Freelancer_application::class, 'freelance_post_id');
    }

    //FreelancerPost belongs to one Skill
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }
}
