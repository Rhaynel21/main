<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'title', 'slug', 'body', 'images', 'video', 'subforum_id'];

    protected static function booted()
    {
        static::creating(function ($post) {
            $post->slug = Str::random(6).'-'.Str::slug(Str::limit($post->title, 8));
        });
    }
    
    
    protected $casts = [
        'images' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subforum()
    {
        return $this->belongsTo(Subforum::class);
    }

    // Get only top-level comments (where parent_id is null).
    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }
    
    public function votes()
    {
        return $this->hasMany(PostVote::class);
    }

    public function likesCount()
    {
        return $this->votes()->where('vote', 1)->count();
    }

    public function dislikesCount()
    {
        return $this->votes()->where('vote', -1)->count();
    }
    
    public function voteCount()
    {
        return $this->likesCount() - $this->dislikesCount();
    }
    
    public function totalCommentCount()
    {
        $count = $this->comments()->count();
        foreach($this->comments as $comment){
            $count += $this->countNestedComments($comment);
        }
        return $count;
    }

    protected function countNestedComments($comment)
    {
        $count = $comment->replies()->count();
        foreach($comment->replies as $reply){
            $count += $this->countNestedComments($reply);
        }
        return $count;
    }
    
    public function hides()
    {
        return $this->hasMany(HiddenPost::class);
    }
}