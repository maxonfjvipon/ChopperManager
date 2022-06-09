<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Http\Requests\RqStorePumpBrand;
use Symfony\Component\HttpFoundation\Response;

/**
 * Store pump brand endpoint.
 */
final class EpStorePumpBrand extends Controller
{
    /**
     * @param RqStorePumpBrand $request
     * @return Responsable|Response
     */
    public function __invoke(RqStorePumpBrand $request): Responsable|Response
    {
        return (new TkWithCallback(
            fn() => PumpBrand::create($request->validated()),
            new TkRedirectToRoute('pump_series.index')
        ))->act($request);
    }
}
