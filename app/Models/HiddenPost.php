<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HiddenPost extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','post_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
