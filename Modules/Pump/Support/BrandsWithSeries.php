<?php

namespace Modules\Pump\Support;

use Illuminate\Support\Facades\Auth;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrayableOf;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpSeries;
use function PHPUnit\Framework\callback;

class BrandsWithSeries implements Arrayable
{

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        $ids = Auth::user()->available_series()
            ->categorized()
            ->pluck('id')
            ->all();

        PumpBrand::with(['series' => function ($query) {
            $query->notDiscontinued()->available();
        }, 'series.types', 'series.applications', 'series.power_adjustments'])
            ->where('series', function ($query) {
                $query->notDiscontinued()->available();
            });
    }
}
