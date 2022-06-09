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
 * Restore pump brand endpoint
 */
final class EpRestorePumpBrand extends Controller
{
    /**
     * @param int $brand_id
     * @return Responsable|Response
     */
    public function __invoke(int $brand_id): Responsable|Response
    {
        return (new TkWithCallback(
            fn() => PumpBrand::withTrashed()->find($brand_id)->restore(),
            new TkRedirectToRoute('pump_series.index')
        ))->act();
    }
}
