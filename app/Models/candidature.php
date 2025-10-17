<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class candidature extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'location',
        'phone',
        'email',
        'status' // pending, validated, rejected
    ];

}
