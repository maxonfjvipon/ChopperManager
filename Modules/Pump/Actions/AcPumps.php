<?php

namespace Modules\Pump\Actions;

use App\Support\ArrForFiltering;
use App\Support\ArrForFilteringWithId;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Pump\Entities\CollectorSwitch;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpOrientation;
use Modules\PumpSeries\Entities\PumpBrand;

/**
 * Pumps action.
 */
final class AcPumps extends ArrEnvelope
{
    /**
     * Ctor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct(
            new ArrMerged(
                new ArrObject(
                    'filter_data',
                    new ArrMerged(
                        new ArrForFilteringWithId([
                            'brands' => ($brandsWithSeries = PumpBrand::with(['series' => fn ($query) => $query->select('id', 'name', 'brand_id')])
                                ->get(['id', 'name']))
                                ->all(),
                            'series' => array_merge(...$brandsWithSeries->map(fn (PumpBrand $brand) => $brand->series->all())),
                            'connection_types' => ConnectionType::asArrayForSelect(),
                            'pump_orientations' => PumpOrientation::asArrayForSelect(),
                            'collector_switches' => CollectorSwitch::asArrayForSelect(),
                        ]),
                        new ArrForFiltering(['dns' => DN::values()])
                    )
                ),
                ['pumps_total' => Pump::allOrCached()->count()]
            )
        );
    }
}
