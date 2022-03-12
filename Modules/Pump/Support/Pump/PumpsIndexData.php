<?php

namespace Modules\Pump\Support\Pump;

use App\Support\ArrForFiltering;
use Illuminate\Support\Facades\Auth;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\MainsConnection;
use Modules\Pump\Entities\PumpApplication;
use Modules\Pump\Entities\PumpType;

/**
 * Pumps index filter data
 */
final class PumpsIndexData implements Arrayable
{
    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        $availableSeriesIds = ($availableSeries = Auth::user()->available_series)->pluck('id')->all();
        return (new ArrMerged(
            new ArrObject(
                "filter_data",
                new ArrForFiltering(
                    new ArrMerged([
                        'brands' => Auth::user()->available_brands()->distinct()->pluck('pump_brands.name')->all(),
                        'series' => $availableSeries->pluck('name')->all(),
                        'connection_types' => ConnectionType::allOrCached()->pluck('name')->all(),
                        'dns' => DN::allOrCached()->pluck('value')->all(),
                        'power_adjustments' => ElPowerAdjustment::allOrCached()->pluck('name')->all(),
                        'types' => PumpType::availableForUserSeries($availableSeriesIds)->pluck('name')->all(), // todo fix
                        'applications' => PumpApplication::availableForUserSeries($availableSeriesIds)->pluck('name')->all(),
                    ], new ArrObject(
                        "mains_connections",
                        new ArrMapped(
                            MainsConnection::allOrCached()->all(),
                            fn(MainsConnection $mc) => $mc->full_value
                        )
                    ))
                )
            ),
            ['projects' => Auth::user()->projects()->get(['id', 'name'])->all()]
        ))->asArray();
    }
}
