<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\PumpSeries\Http\Requests\RqUpdatePumpSeries;

/**
 * Update pump series endpoint.
 */
final class EpUpdatePumpSeries extends TakeEndpoint
{
    /**
     * Ctor.
     * @param RqUpdatePumpSeries $request
     */
    public function __construct(RqUpdatePumpSeries $request)
    {
        parent::__construct(
            new TkWithCallback(
                fn() => PumpSeries::find($request->pump_series)->update($request->validated()),
                new TkRedirectToRoute('pump_series.index')
            )
        );
    }
}
