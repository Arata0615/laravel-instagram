<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    public $timestamps = false;

    # To get the info of the follower
    public function follower()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    # To get the info of the following
    public function following()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
