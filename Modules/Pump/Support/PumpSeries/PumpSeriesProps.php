<?php

namespace Modules\Pump\Support\PumpSeries;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\PumpApplication;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpCategory;
use Modules\Pump\Entities\PumpType;

final class PumpSeriesProps implements Arrayable
{
    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return [
            'pump_series_props' => [
                'data' => [
                    'brands' => PumpBrand::all(),
                    'categories' => PumpCategory::allOrCached(),
                    'power_adjustments' => ElPowerAdjustment::allOrCached(),
                    'applications' => PumpApplication::allOrCached(),
                    'types' => PumpType::allOrCached()
                ]
            ]
        ];
    }
}
