<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\PumpSeries\Entities\PumpSeries;
use Symfony\Component\HttpFoundation\Response;

/**
 * Destroy pump series endpoint.
 */
final class EpDestroyPumpSeries extends Controller
{
    /**
     * @param PumpSeries $pumpSeries
     * @return Responsable|Response
     */
    public function __invoke(PumpSeries $pumpSeries): Responsable|Response
    {
        return (new TkWithCallback(
            fn() => $pumpSeries->delete(),
            new TkRedirectToRoute('pump_series.index')
        ))->act();
    }
}
