<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectBack;
use App\Takes\TkWithCallback;
use Illuminate\Http\Request;
use Modules\PumpSeries\Entities\PumpSeries;

/**
 * Restore pump series endpoint.
 */
final class EpRestorePumpSeries extends TakeEndpoint
{
    /**
     * Ctor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkWithCallback(
                fn() => PumpSeries::withTrashed()->find($request->pump_series)->restore(),
                new TkRedirectBack()
            )
        );
    }
}
