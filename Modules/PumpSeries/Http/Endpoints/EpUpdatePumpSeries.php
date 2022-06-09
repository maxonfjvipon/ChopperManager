<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\PumpSeries\Http\Requests\RqUpdatePumpSeries;
use Symfony\Component\HttpFoundation\Response;

/**
 * Update pump series endpoint.
 */
final class EpUpdatePumpSeries extends Controller
{
    /**
     * @param RqUpdatePumpSeries $request
     * @param PumpSeries $pumpSeries
     * @return Responsable|Response
     */
    public function __invoke(RqUpdatePumpSeries $request, PumpSeries $pumpSeries): Responsable|Response
    {
        return (new TkWithCallback(
            fn() => $pumpSeries->update($request->validated()),
            new TkRedirectToRoute('pump_series.index')
        ))->act($request);
    }
}
