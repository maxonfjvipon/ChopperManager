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
     * Ctor wrap.
     * @return PumpsIndexData
     */
    public static function new(): PumpsIndexData
    {
        return new self();
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        $availableSeries = Auth::user()->available_series;
        $availableSeriesIds = $availableSeries->pluck('id')->all();
        return ArrMerged::new(
            ArrObject::new(
                "filter_data",
                ArrForFiltering::new(
                    ArrMerged::new(
                        [
                            'brands' => Auth::user()->available_brands()->distinct()->pluck('pump_brands.name')->all(),
                            'series' => $availableSeries->pluck('name')->all(),
                            'connection_types' => ConnectionType::allOrCached()->pluck('name')->all(),
                            'dns' => DN::allOrCached()->pluck('value')->all(),
                            'power_adjustments' => ElPowerAdjustment::allOrCached()->pluck('name')->all(),
                            'types' => PumpType::availableForUserSeries($availableSeriesIds)->pluck('name')->all(), // todo fix
                            'applications' => PumpApplication::availableForUserSeries($availableSeriesIds)->pluck('name')->all(),
                        ],
                        ArrObject::new(
                            "mains_connections",
                            ArrMapped::new(
                                MainsConnection::allOrCached()->all(),
                                fn(MainsConnection $mc) => $mc->full_value
                            )
                        )
                    )
                )
            ),
            ['projects' => Auth::user()->projects()->get(['id', 'name'])->all()]
        )->asArray();
    }
}
