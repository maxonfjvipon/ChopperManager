<?php

namespace Modules\Pump\Support\Pump\LoadedPumps;

use Illuminate\Database\Eloquent\Builder;
use Modules\Pump\Entities\Pump;

/**
 * Loaded double pumps
 */
final class DPLoaded implements LoadedPumps
{
    /**
     * @inheritDoc
     */
    public function loaded(): Builder
    {
        return Pump::with([
            'series',
            'series.brand',
            'series.power_adjustment',
            'series.category',
            'series.applications',
            'series.types'
        ])
            ->with('mains_connection')
            ->with('dn_suction')
            ->with('dn_pressure')
            ->with('connection_type')
            ->with(['price_list', 'price_list.currency'])
            ->doublePumps();
    }
}
