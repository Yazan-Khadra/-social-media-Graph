<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'files',
        'description',
        'project_id',
        'title',
        'privacy',
        'admin_id'
    ];
    protected $casts = [
        'files' => 'array'
    ];
    // user of posts
    public function Students() {
        return $this->belongsToMany(Student::class,"_posts__users__pivot",'post_id','user_id');
    }
    //the post project
    public function Project() {
        return $this->belongsTo(Project::class,'project_id');
    }
    public function Comments() {
        return $this->hasMany(Comment::class,'post_id');
    }
    public function Reactions() {
        return $this->belongsToMany(Reaction::class,'reactions_posts_pivot','post_id','reaction_id');
    }
    //hashtags
    public function hashtags()
    {
    return $this->belongsToMany(Hashtag::class, 'hashtag_post');
    }

}
