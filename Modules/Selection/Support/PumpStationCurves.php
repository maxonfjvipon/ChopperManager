<?php

namespace Modules\Selection\Support;

use App\Support\TxtView;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Selection\Entities\PumpStation;
use Modules\Selection\Traits\AxisStep;

/**
 * Selection curves view as {@Text}.
 */
final class PumpStationCurves extends ArrEnvelope
{
    use AxisStep;

    /**
     * Ctor.
     *
     * @throws Exception
     */
    public function __construct(
        private PumpStation $pumpStation,
        private string $viewName = 'curves'
    ) {
        parent::__construct(
            new ArrObject(
                $this->viewName,
                new TxtView(
                    'selection::pump_station_curves',
                    $this->pumpStation
                        ->withCurves()
                        ->curves
                )
            )
        );
    }
}
