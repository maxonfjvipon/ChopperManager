<?php

namespace Modules\Selection\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Components\Entities\Collector;
use Modules\Components\Entities\ControlSystem;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\Selection;

trait PumpStationRelationships
{
    // RELATIONSHIPS
    /**
     * @return BelongsTo
     */
    public function collector(): BelongsTo
    {
        return $this->belongsTo(Collector::class, 'collector_id');
    }

    /**
     * @return BelongsTo
     */
    public function control_system(): BelongsTo
    {
        return $this->belongsTo(ControlSystem::class, 'control_system_id');
    }

    /**
     * @return BelongsTo
     */
    public function pump(): BelongsTo
    {
        return $this->belongsTo(Pump::class, 'pump_id');
    }

    /**
     * @return BelongsTo
     */
    public function selection(): BelongsTo
    {
        return $this->belongsTo(Selection::class, 'selection_id');
    }
}
