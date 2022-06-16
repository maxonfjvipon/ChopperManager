<?php

namespace Modules\Selection\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Project\Entities\Project;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Traits\HasJockeyPump;
use Modules\Selection\Entities\PumpStation;

/**
 * Selection relationships
 * @package Modules\Selection\Traits
 */
trait SelectionRelationships
{
    use HasJockeyPump;

    /**
     * @return HasMany
     */
    public function pump_stations(): HasMany
    {
        return $this->hasMany(PumpStation::class, 'selection_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
