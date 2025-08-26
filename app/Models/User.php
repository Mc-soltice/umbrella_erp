<?php

namespace App\Models;

use App\Models\LoginAttemps;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles,LogsActivity;

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

        /**
     * Configure les options de journalisation pour ce modèle.
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['first_name', 'last_name', 'email', 'phone', 'is_locked'])
            ->useLogName('user')
            ->setDescriptionForEvent(function (string $eventName) {
                return "User model a subi l'événement : {$eventName}";
            });
    }
}
