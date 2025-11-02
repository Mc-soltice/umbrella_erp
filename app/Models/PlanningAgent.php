<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\PlanningStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Entrée d'agent dans un planning (pivot enrichi).
 *
 * @property int $id
 * @property int $planning_id
 * @property int $agent_id
 * @property string $shift
 * @property string|PlanningStatus $status
 * @property string|null $motif
 * @property int|null $remplacant_id
 */
class PlanningAgent extends Model
{
    use HasFactory;

    protected $table = 'planning_agents';

    protected $fillable = ['planning_id', 'agent_id', 'shift', 'status', 'motif', 'remplacant_id'];

    protected $casts = [
        // si tu veux caster en enum :
        // 'status' => PlanningStatus::class,
    ];

    public function planning(): BelongsTo
    {
        return $this->belongsTo(Planning::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function remplacant(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'remplacant_id');
    }
}
