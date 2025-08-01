<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class skiil_group_pivot extends Model
{
    protected $fillable = [
        'group_id',
        'skill_id'
    ];
}
