<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorize;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\PumpSeries\Entities\PumpBrand;
use Symfony\Component\HttpFoundation\Response;

/**
 * Destroy pump brand endpoint.
 */
final class EpDestroyPumpBrand extends Controller
{
    /**
     * @param PumpBrand $pumpBrand
     * @return Responsable|Response
     */
    public function __invoke(PumpBrand $pumpBrand): Responsable|Response
    {
        return (new TkWithCallback(
            fn() => $pumpBrand->delete(),
            new TkRedirectToRoute('pump_series.index')
        ))->act();
    }
}
