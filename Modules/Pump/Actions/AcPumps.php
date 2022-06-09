<?php

namespace Modules\Pump\Actions;

use App\Support\ArrForFiltering;
use App\Support\ArrForFilteringWithId;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Pump\Entities\CollectorSwitch;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpOrientation;
use Modules\Pump\Http\Requests\RqLoadPumps;
use Modules\Pump\Transformers\RcPump;
use Modules\Pump\Transformers\RcPumpToShow;
use Modules\PumpSeries\Entities\PumpBrand;

final class AcPumps
{
    /**
     * @return array
     * @throws Exception
     */
    public function __invoke(): array
    {
        $brandsWithSeries = PumpBrand::with(['series' => function ($query) {
            $query->select('id', 'name', 'brand_id');
        }])->get(['id', 'name']);
        $series = array_merge(...$brandsWithSeries->map(fn(PumpBrand $brand) => $brand->series->all()));
        return [
            "filter_data" => (new ArrMerged(
                new ArrForFilteringWithId([
                    'brands' => $brandsWithSeries->all(),
                    'series' => $series,
                    'connection_types' => ConnectionType::asArrayForSelect(),
                    'pump_orientations' => PumpOrientation::asArrayForSelect(),
                    'collector_switches' => CollectorSwitch::asArrayForSelect()
                ]),
                new ArrForFiltering(['dns' => DN::values()])
            ))->asArray(),
            'pumps_total' => Pump::allOrCached()->count(),
        ];
    }
}
