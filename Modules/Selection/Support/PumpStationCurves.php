<?php

namespace Modules\Selection\Support;

use App\Support\TxtView;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Selection\Entities\PumpStation;
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
     * @param string $viewName
     */
    public function __construct(
        private PumpStation $pumpStation,
        private string $viewName = "curves"
    )
    {
    }

    /**
     * @return array
     * @throws Exception
     */
    public function asArray(): array
    {
        return [$this->viewName => TxtView::new(
            "selection::pump_station_curves",
            $this->pumpStation
                ->withCurves()
                ->curves
        )->asString()];
    }
}
