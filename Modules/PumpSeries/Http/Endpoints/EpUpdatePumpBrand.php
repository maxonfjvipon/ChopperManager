<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Http\Requests\RqUpdatePumpBrand;

/**
 * Update pump brand endpoint.
 */
final class EpUpdatePumpBrand extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct(RqUpdatePumpBrand $request)
    {
        parent::__construct(
            new TkWithCallback(
                fn () => PumpBrand::find($request->pump_brand)->update($request->validated()),
                new TkRedirectToRoute('pump_series.index')
            )
        );
    }
}
