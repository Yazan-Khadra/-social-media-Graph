<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Year;
use App\Models\Major;

class Project extends Model
{
    protected $fillable = ['name', 'description'];

    public function year(){
        return $this->belongsTo(Year::class);
    }

    public function major(){
        return $this->belongsTo(Major::class);
    }
}
