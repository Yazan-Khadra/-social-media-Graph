<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class identity extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'image_url'
    ];
}
