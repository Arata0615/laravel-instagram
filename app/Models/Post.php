<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    # Post belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
    
    # To get all the categories related to this post
    public function categoryPost()
    {
        return $this->hasMany(CategoryPost::class);
    }

    # Post has many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    # A post has many likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    # return true if the post is already liked
    public function isLiked()
    {
        return $this->likes()->where('user_id', Auth::user()->id)->exists();
    }
}
