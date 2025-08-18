<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    protected $fillable = [
        'user_id',
        'otp',
        'exexpires_at'
    ];
    protected $table = 'email_otps';
}
