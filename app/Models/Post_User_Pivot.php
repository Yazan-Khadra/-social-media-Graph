<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post_User_Pivot extends Model
{
        protected $table = '_posts__users__pivot';
    protected $fillable = ['user_id','post_id'];
}
