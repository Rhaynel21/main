<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Comment extends Model
{
    use HasFactory;
    
    protected $fillable = ['slug','post_id','user_id','parent_id','body'];

    protected static function booted()
    {
        static::creating(function ($c) {
            // 8-char base from body or id if empty
            $base = Str::slug(Str::limit($c->body, 8, '')) ?: 'c';
            $c->slug = Str::random(6) . '-' . $base;
        });
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Recursive relationship for nested replies.
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')
                    ->with('user', 'replies');
    }
    
    // Relationship for votes.
    public function votes()
    {
        return $this->hasMany(CommentVote::class);
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
    
}
