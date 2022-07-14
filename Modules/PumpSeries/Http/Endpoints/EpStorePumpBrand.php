<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Http\Requests\RqStorePumpBrand;

/**
 * Store pump brand endpoint.
 */
final class EpStorePumpBrand extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct(RqStorePumpBrand $request)
    {
        parent::__construct(
            new TkWithCallback(
                fn () => PumpBrand::create($request->validated()),
                new TkRedirectToRoute('pump_series.index')
            )
        );
    }
}
