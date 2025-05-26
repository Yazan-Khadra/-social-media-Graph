<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $fillable=['major_name'];

    //each major has many students
    public function users(){

        return $this->hasMany(User::class);
    }
    //each major has many projects
    public function projects(){
        return $this->hasMany(Project::class,'major_id','id');
    }
}
