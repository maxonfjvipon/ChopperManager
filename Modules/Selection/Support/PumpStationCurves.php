<?php

namespace Modules\Selection\Support;

use App\Support\TxtView;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\PumpStation;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Traits\AxisStep;

/**
 * Selection curves view as {@Text}
 */
final class PumpStationCurves implements Arrayable
{
    use AxisStep;

    /**
     * Ctor.
     * @param PumpStation $pumpStation
     */
    public function __construct(private PumpStation $pumpStation)
    {
    }

    /**
     * @return array
     * @throws Exception
     */
    public function asArray(): array
    {
        return ['curves' => (new TxtView(
            "selection::pump_station_curves",
            $this->pumpStation
                ->withCurves()
                ->curves
        ))->asString()];
    }
}
