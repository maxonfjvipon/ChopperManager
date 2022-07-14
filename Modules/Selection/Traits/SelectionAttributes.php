<?php

namespace Modules\Selection\Traits;

use JetBrains\PhpStorm\Pure;
use Modules\Pump\Entities\Pump;

/**
 * Selection attributes.
 */
trait SelectionAttributes
{
    // ATTRIBUTES
    public function getPumpRatedPowerAttribute()
    {
        return match ($this->pump_type) {
            Pump::$SINGLE_PUMP => $this->pump->rated_power,
            Pump::$DOUBLE_PUMP => $this->total_rated_power,
        };
    }

    public function getTotalPumpsCountAttribute()
    {
        return match ($this->pump_type) {
            Pump::$SINGLE_PUMP => $this->pumps_count,
            Pump::$DOUBLE_PUMP => 1,
        };
    }

    #[Pure]
 public function getTotalRatedPowerAttribute(): float|int
 {
     return match ($this->pump_type) {
         Pump::$SINGLE_PUMP => round(
                $this->pump->rated_power * $this->pumps_count, 4
            ),
            Pump::$DOUBLE_PUMP => $this->pump->rated_power * 2,
     };
 }

    public function getPumpTypeAttribute()
    {
        return $this->pump->pumpable_type;
    }
}
