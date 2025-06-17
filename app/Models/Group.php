<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = [
        'group_name',
        'admin_id',
        'year_id'
    ];


    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }


    public function members(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function year(): BelongsTo
    {
        return $this->belongsTo(Year::class);
    }

    public function invitations()
    {
        return $this->hasMany(GroupInvitation::class);
    }

}
