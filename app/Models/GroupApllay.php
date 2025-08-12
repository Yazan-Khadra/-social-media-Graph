<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupApllay extends Model
{
    protected $fillable = [
        "student_id",
        "group_id",
        "skill_id",
        "post_id",
        "status"
    ];
    public function Group() {
        return $this->belongsTo(Group::class,'group_id');
    }
}
