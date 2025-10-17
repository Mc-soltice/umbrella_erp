<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricule',
        'first_name',
        'last_name',
        'location',
        'phone',
        'email',
        'site_id',
        'password'
    ];

    // Relation avec site
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
