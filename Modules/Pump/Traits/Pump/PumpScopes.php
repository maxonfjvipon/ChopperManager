<?php

namespace Modules\Pump\Traits\Pump;

use Illuminate\Support\Facades\Auth;
use Modules\Pump\Entities\PumpCategory;
use Modules\Pump\Entities\PumpSeries;

trait PumpScopes
{
    public function scopePerformanceData($query, $seriesId)
    {
        return match (PumpSeries::find($seriesId)->category_id) {
            PumpCategory::$SINGLE_PUMP => $query->addSelect('sp_performance'),
            PumpCategory::$DOUBLE_PUMP => $query->addSelect('dp_peak_performance', 'dp_standby_performance'),
        };
    }

    public function scopeOnPumpableType($query, $pumpable_type)
    {
        return $query->where('pumpable_type', $pumpable_type);
    }

    public function scopeDoublePumps($query)
    {
        return $this->scopeOnPumpableType($query, self::$DOUBLE_PUMP);
    }

    public function scopeSinglePumps($query)
    {
        return $this->scopeOnPumpableType($query, self::$SINGLE_PUMP);
    }

    public function scopeAvailableForCurrentUser($query)
    {
        return $query->whereIn('id', Auth::user()->available_pumps()->pluck('pumps.id')->all());
    }
}
