<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Year extends Model
{
    protected $fillable=['Year_name'];


     //each year has many student

    public function Students(): HasMany
    {
        return $this->hasMany(User::class, 'year_id', 'id');
    }
}
