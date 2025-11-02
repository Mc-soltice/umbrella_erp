<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Planning : un planning correspond à un site pour une date donnée.
 *
 * @property int $id
 * @property int $site_id
 * @property \Illuminate\Support\Carbon $date
 * @property int $created_by
 */
class Planning extends Model
{
    use HasFactory;

    protected $fillable = ['site_id', 'date', 'created_by'];

    protected $casts = [
        'date' => 'date:Y-m-d', // garantira Carbon
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function agents(): HasMany
    {
        return $this->hasMany(PlanningAgent::class);
    }
}
