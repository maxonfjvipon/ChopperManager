<?php

namespace Modules\ProjectParticipant\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\ProjectParticipant\Entities\DealerPumpSeries;
use Modules\ProjectParticipant\Http\Requests\RqStoreDealer;

/**
 * Store dealer endpoint.
 */
final class EpStoreDealer extends TakeEndpoint
{
    /**
     * Ctor.
     * @param RqStoreDealer $request
     */
    public function __construct(RqStoreDealer $request)
    {
        parent::__construct(
            new TkWithCallback(
                fn() => DealerPumpSeries::updateSeriesForDealer(
                    $request->available_series_ids ?? [],
                    Dealer::create($request->dealerProps())
                ),
                new TkRedirectToRoute('dealers.index')
            )
        );
    }
}
