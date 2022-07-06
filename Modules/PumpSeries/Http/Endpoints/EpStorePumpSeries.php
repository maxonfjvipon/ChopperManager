<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\PumpSeries\Http\Requests\RqStorePumpSeries;
use Modules\ProjectParticipant\Entities\Contractor;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\User\Entities\User;

/**
 * Store pump series endpoint.
 */
final class EpStorePumpSeries extends TakeEndpoint
{
    /**
     * Ctor.
     * @param RqStorePumpSeries $request
     */
    public function __construct(RqStorePumpSeries $request)
    {
        parent::__construct(
            new TkWithCallback(
                fn() => Dealer::allowNewSeriesToBPE(PumpSeries::create($request->validated())),
                new TkRedirectToRoute('pump_series.index')
            )
        );
    }
}
