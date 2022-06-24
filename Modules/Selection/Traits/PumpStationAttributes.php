<?php

namespace Modules\Selection\Traits;

/**
 * Pump station attributes
 */
trait PumpStationAttributes
{

    /**
     * @return float
     */
    public function getHeadAttribute(): float
    {
        return $this->attributes['head'] ?? $this->selection->head;
    }

    /**
     * @return float
     */
    public function getFlowAttribute(): float
    {
        return $this->attributes['flow'] ?? $this->selection->flow;
    }

    /**
     * @return int
     */
    public function getPumpsCountAttribute(): int
    {
        return $this->attributes['pumps_count'] ?? $this->main_pumps_count + $this->reserve_pumps_count;
    }
}
