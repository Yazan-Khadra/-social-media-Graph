<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\GroupStudentProject;
use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    protected $fillable = [
        'group_name',
        'admin_id',
        'project_id'
    ];


    public function admin(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'admin_id');
    }


    public function members(): BelongsToMany
    {
       return $this->belongsToMany(Student::class, 'group_student_project', 'group_id', 'student_id')
                ->withPivot('is_admin');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function invitations()
    {
        return $this->hasMany(GroupInvitation::class);
    }

    public function groupStudentProjects()
    {
        return $this->hasMany(GroupStudentProject::class, 'group_id');
    }


}
