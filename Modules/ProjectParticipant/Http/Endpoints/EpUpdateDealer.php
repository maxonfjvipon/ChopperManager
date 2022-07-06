<?php

namespace Modules\ProjectParticipant\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\ProjectParticipant\Entities\DealerPumpSeries;
use Modules\ProjectParticipant\Http\Requests\RqUpdateDealer;

/**
 * Update dealer endpoint.
 */
final class EpUpdateDealer extends TakeEndpoint
{
    /**
     * Ctor.
     * @param RqUpdateDealer $request
     */
    public function __construct(RqUpdateDealer $request)
    {
        parent::__construct(
            new TkWithCallback(
                function() use ($request) {
                    ($dealer = Dealer::find($request->dealer))->update($request->dealerProps());
                    DealerPumpSeries::updateSeriesForDealer($request->available_series_ids, $dealer);
                },
                new TkRedirectToRoute('dealers.index')
            )
        );
    }
}
