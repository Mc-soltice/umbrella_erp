<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Attendance : ligne de comptabilisation par agent/jour (calcul paie).
 *
 * @property int $id
 * @property int $agent_id
 * @property int|null $planning_agent_id
 * @property \Illuminate\Support\Carbon $date
 * @property string $status (WORKED/ABSENT/REST/REPLACEMENT)
 */
class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['agent_id', 'planning_agent_id', 'date', 'status', 'reason'];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function planningAgent(): BelongsTo
    {
        return $this->belongsTo(PlanningAgent::class);
    }
}
