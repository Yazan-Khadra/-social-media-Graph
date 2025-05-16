<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $fillable=['major_name'];

    //each major has many student
    public function users(): HasMany{

        return $this->hasMany(User::class);
    }
}
