<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\PumpSeries\Support\PumpSeriesProps;
use Modules\PumpSeries\Transformers\RcPumpSeriesProps;
use Symfony\Component\HttpFoundation\Response;

/**
 * Create pump series endpoint.
 */
final class EpCreatePumpSeries extends Controller
{
    /**
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(): Responsable|Response
    {
        return (new TkInertia(
            "PumpSeries::Create",
            new PumpSeriesProps()
        ))->act();
    }
}
