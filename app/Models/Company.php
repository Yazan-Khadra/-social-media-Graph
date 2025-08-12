<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;
    public $incrementing = false;

    protected $fillable = [
        'id',
        'company_name',
        'description',
        'email',
        'social_links',
        'mobile_number',
        'logo_url',
        'user_id'
    ];

    protected $casts = [
        'social_links' => 'array',
    ];

    // Company has many FreelancerPosts
    public function freelancerPosts(): HasMany
    {
        return $this->hasMany(FreelancerPost::class);
    }


}
