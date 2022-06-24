<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Illuminate\Http\Request;
use Modules\PumpSeries\Entities\PumpBrand;

/**
 * Destroy pump series endpoint.
 */
final class EpDestroyPumpSeries extends TakeEndpoint
{
    /**
     * Ctor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkWithCallback(
                fn() => PumpBrand::find($request->pump_series)->delete(),
                new TkRedirectToRoute('pump_series.index')
            )
        );
    }
}
