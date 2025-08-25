<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    protected $fillable = ['user_id', 'attempts', 'locked_until'];

    protected $dates = ['locked_until'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
