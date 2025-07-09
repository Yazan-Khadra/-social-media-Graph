<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'description',
        'email',
        'phone',
        'social_links',
        'logo_url'
    ];

    // Company has many FreelancerPosts
    public function freelancerPosts(): HasMany
    {
        return $this->hasMany(FreelancerPost::class);
    }
}
