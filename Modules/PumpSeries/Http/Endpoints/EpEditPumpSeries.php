<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\PumpSeries\Support\PumpSeriesAsResource;
use Modules\PumpSeries\Support\PumpSeriesProps;
use Modules\PumpSeries\Transformers\RcPumpSeries;
use Modules\PumpSeries\Transformers\RcPumpSeriesProps;
use Symfony\Component\HttpFoundation\Response;

/**
 * Edit pump series endpoint.
 */
final class EpEditPumpSeries extends Controller
{
    /**
     * @param PumpSeries $pumpSeries
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(PumpSeries $pumpSeries): Responsable|Response
    {
        return (new TkInertia(
            "PumpSeries::Edit",
            new ArrMerged(
                new PumpSeriesProps(),
                new PumpSeriesAsResource($pumpSeries)
            )
        ))->act();
    }
}
