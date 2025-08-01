<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'academic_staff_id',
    ];

    public function academicStaff()
    {
        return $this->belongsTo(AcademicStaff::class);
    }
}
