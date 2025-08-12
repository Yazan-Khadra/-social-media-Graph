<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class commentResponses extends Model
{
    protected $fillable = [
        "comment",
        'comment_id',
        'user_id'
    ];
    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}
