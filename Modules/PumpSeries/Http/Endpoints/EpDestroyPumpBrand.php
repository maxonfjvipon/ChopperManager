<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Illuminate\Http\Request;
use Modules\PumpSeries\Entities\PumpBrand;

/**
 * Destroy pump brand endpoint.
 */
final class EpDestroyPumpBrand extends TakeEndpoint
{
    /**
     * Ctor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkWithCallback(
                fn() => PumpBrand::find($request->pump_brand)->delete(),
                new TkRedirectToRoute('pump_series.index')
            )
        );
    }
}
