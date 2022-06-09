<?php

namespace Modules\Selection\Traits;

/**
 * Pump station attributes
 */
trait PumpStationAttributes
{

    /**
     * @return array|float|mixed
     */
    public function getHeadAttribute(): mixed
    {
        return $this->attributes['head'] ?? $this->selection->head;
    }

    /**
     * @return array|float|mixed
     */
    public function getFlowAttribute(): mixed
    {
        return $this->attributes['flow'] ?? $this->selection->flow;
    }

    /**
     * @return int|mixed
     */
    public function getPumpsCountAttribute()
    {
        return $this->attributes['pumps_count'] ?? $this->main_pumps_count + $this->reserve_pumps_count;
    }
}
