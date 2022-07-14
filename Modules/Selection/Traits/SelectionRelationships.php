<?php

namespace Modules\Selection\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Project\Entities\Project;
use Modules\Pump\Traits\HasJockeyPump;
use Modules\Selection\Entities\PumpStation;

/**
 * Selection relationships.
 */
trait SelectionRelationships
{
    use HasJockeyPump;

    public function pump_stations(): HasMany
    {
        return $this->hasMany(PumpStation::class, 'selection_id', 'id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
