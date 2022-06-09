<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\PumpSeries\Http\Requests\RqStorePumpSeries;
use Modules\User\Entities\User;
use Symfony\Component\HttpFoundation\Response;

/**
 * Store pump series endpoint.
 */
final class EpStorePumpSeries extends Controller
{
    /**
     * @param RqStorePumpSeries $request
     * @return Responsable|Response
     */
    public function __invoke(RqStorePumpSeries $request): Responsable|Response
    {
        return (new TkWithCallback(
            fn() => User::allowNewSeriesToAdmins(PumpSeries::create($request->validated())),
            new TkRedirectToRoute('pump_series.index')
        ))->act($request);
    }
}
