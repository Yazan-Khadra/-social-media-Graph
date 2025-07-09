<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\GroupStudentProject;
use App\Models\Project;

class Group extends Model
{
    protected $fillable = [
        'group_name',
        'admin_id',
        'project_id'
    ];


    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }


    public function members(): HasMany
    {
        return $this->hasMany(User::class);
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
