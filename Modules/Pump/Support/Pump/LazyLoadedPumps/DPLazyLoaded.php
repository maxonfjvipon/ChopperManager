<?php

namespace Modules\Pump\Support\Pump\LazyLoadedPumps;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Modules\Pump\Entities\Pump;

final class DPLazyLoaded implements LazyLoadedPumps
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function lazyLoaded(): Collection
    {
        return Pump::allOrCached()->load([
            'series',
            'series.brand',
            'series.power_adjustment',
            'series.category',
            'series.applications',
            'series.types',
            'mains_connection',
            'dn_suction',
            'dn_pressure',
            'connection_type',
            'price_lists',
            'price_lists.currency'
        ])->where('pumpable_type', Pump::$DOUBLE_PUMP);
    }
}
