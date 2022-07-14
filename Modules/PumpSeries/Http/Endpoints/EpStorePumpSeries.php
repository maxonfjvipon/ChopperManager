<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\PumpSeries\Http\Requests\RqStorePumpSeries;

/**
 * Store pump series endpoint.
 */
final class EpStorePumpSeries extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct(RqStorePumpSeries $request)
    {
        parent::__construct(
            new TkWithCallback(
                fn () => Dealer::allowNewSeriesToBPE(PumpSeries::create($request->validated())),
                new TkRedirectToRoute('pump_series.index')
            )
        );
    }
}
