<?php

namespace App\Models;

use App\Models\LoginAttemps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles;

    protected $fillable = [
        'matricule',
        'first_name',
        'last_name',
        'phone',
        'email',
        'password',
        'is_locked'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Génération auto du matricule
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->matricule = 'UIS' . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        });
    }

    public function loginAttempt()
    {
        return $this->hasOne(LoginAttempt::class);
    }

}
