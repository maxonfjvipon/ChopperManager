<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Http\Requests\RqUpdatePumpBrand;
use Symfony\Component\HttpFoundation\Response;

/**
 * Update pump brand endpoint.
 */
final class EpUpdatePumpBrand extends Controller
{
    /**
     * @param RqUpdatePumpBrand $request
     * @param PumpBrand $pumpBrand
     * @return Responsable|Response
     */
    public function __invoke(RqUpdatePumpBrand $request, PumpBrand $pumpBrand): Responsable|Response
    {
        return (new TkWithCallback(
            fn() => $pumpBrand->update($request->validated()),
            new TkRedirectToRoute('pump_series.index')
        ))->act($request);
    }
}
