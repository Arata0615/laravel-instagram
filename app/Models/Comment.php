<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    # Comment belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
