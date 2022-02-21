<?php

namespace Modules\Pump\Http\Endpoints\PumpSeries;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Http\Requests\PumpSeriesUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

final class PumpSeriesUpdateEndpoint extends Controller
{
    /**
     * @param PumpSeriesUpdateRequest $request
     * @param PumpSeries $pumpSeries
     * @return Responsable|Response
     */
    public function __invoke(PumpSeriesUpdateRequest $request, PumpSeries $pumpSeries): Responsable|Response
    {
        return TkAuthorized::new(
            'series_edit',
            TkWithCallback::new(
                fn() => $pumpSeries->updateFromRequest($request),
                TkRedirectedRoute::new('pump_series.index')
            )
        )->act($request);
    }
}
