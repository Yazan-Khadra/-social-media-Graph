<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable=['name','logo_url'];
    public function Groups_Posts() {
        return $this->belongsToMany(GroupPost::class,'skiil_group_pivots','skill_id','group_id');
    }

}
