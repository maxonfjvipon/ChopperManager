<?php

namespace Modules\Pump\Http\Endpoints\PumpSeries;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Http\Requests\PumpSeriesStoreRequest;
use Modules\User\Entities\User;
use Symfony\Component\HttpFoundation\Response;

final class PumpSeriesStoreEndpoint extends Controller
{
    /**
     * @param PumpSeriesStoreRequest $request
     * @return Responsable|Response
     */
    public function __invoke(PumpSeriesStoreRequest $request): Responsable|Response
    {
        return (new TkAuthorized(
            "series_create",
            new TkWithCallback(
                fn () => User::addNewSeriesForSuperAdmins(PumpSeries::createFromRequest($request)),
                new TkRedirectedRoute('pump_series.index')
            )
        ))->act($request);
    }
}
