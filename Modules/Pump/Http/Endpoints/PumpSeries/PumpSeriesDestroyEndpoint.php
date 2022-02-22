<?php

namespace Modules\Pump\Http\Endpoints\PumpSeries;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Pump\Entities\PumpSeries;
use Symfony\Component\HttpFoundation\Response;

final class PumpSeriesDestroyEndpoint extends Controller
{
    /**
     * @param PumpSeries $pumpSeries
     * @return Responsable|Response
     */
    public function __invoke(PumpSeries $pumpSeries): Responsable|Response
    {
        return TkAuthorized::new(
            'series_delete',
            TkWithCallback::new(
                fn() => $pumpSeries->delete(),
                TkRedirectedRoute::new('pump_series.index')
            )
        )->act();
    }
}