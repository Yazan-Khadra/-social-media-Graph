<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Year;
use App\Models\Major;
use App\Models\GroupStudentProject;
use App\Models\Group;

class Project extends Model
{
    protected $fillable = ['name', 'description'];

    public function year(){
        return $this->belongsTo(Year::class);
    }

    public function major(){
        return $this->belongsTo(Major::class);
    }

    public function groupStudentProjects()
    {
        return $this->hasMany(GroupStudentProject::class, 'project_id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
