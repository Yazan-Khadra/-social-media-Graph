<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupPost extends Model
{
    protected $fillable = [
        "description",
        "admin_id",
        'group_id'
    ];
    public function Skills(){
        return $this->belongsToMany(Skill::class,'skiil_group_pivots','group_id','skill_id');
    }
    public function Group() {
        return $this->belongsTo(Group::class,'group_id');
    }
}
