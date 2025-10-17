<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'responsable_id'
    ];

    // Relation avec l'utilisateur responsable
    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    // Relation avec les agents
    public function agents()
    {
        return $this->hasMany(User::class, 'site_id');
    }
}
