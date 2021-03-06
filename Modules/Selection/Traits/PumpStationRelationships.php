<?php

namespace Modules\Selection\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Components\Entities\Chassis;
use Modules\Components\Entities\Collector;
use Modules\Components\Entities\ControlSystem;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Traits\HasJockeyPump;
use Modules\Selection\Entities\Selection;

trait PumpStationRelationships
{
    use HasJockeyPump;

    /**
     * @return BelongsTo
     */
    public function input_collector(): BelongsTo
    {
        return $this->belongsTo(Collector::class, 'input_collector_id');
    }

    /**
     * @return BelongsTo
     */
    public function output_collector(): BelongsTo
    {
        return $this->belongsTo(Collector::class, 'output_collector_id');
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

    /**
     * @return BelongsTo
     */
    public function chassis(): BelongsTo
    {
        return $this->belongsTo(Chassis::class, 'chassis_id');
    }
}
