<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const ADMIN_ROLE_ID =  1;
    const USER_ROLE_ID =  2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    # User has many posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    # To get the followers of a user
    public function followers()
    {
        return $this->hasMany(Follow::class, 'following_id');
        // following_id can show who are folloming me
    }

    # A user can follow many users.
    public function following()
    {
        return $this->hasMany(Follow::class, 'follower_id');
        // serch the follower_id column with the ID to identify
        // the users that I am following
    }

    # Return true if the Auth user is already following this user
    public function isFollowed()
    {
        return $this->followers()->where('follower_id', Auth::user()->id)->exists();
        // Auth::user()->id is the follower_id
        // $this->followers() : get all the followers of the User
        // Where('follower_id', Auth::user()->id) : From thae lost, serch for the Auth user from the follower columun
    }

    public function isFollowing()
    {
        return $this->following()->where('following_id', Auth::user()->id)->exists();
    }
}
