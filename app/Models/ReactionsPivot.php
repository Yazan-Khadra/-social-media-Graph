<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class ReactionsPivot extends Model
{
    protected $table = "reactions_posts_pivot";
    protected $fillable = [
        'post_id',
        'user_id',
        'reaction_id'
    ];
  

    // Relation to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation to Reaction
    public function reaction()
    {
        return $this->belongsTo(Reaction::class, 'reaction_id');
    }
}


