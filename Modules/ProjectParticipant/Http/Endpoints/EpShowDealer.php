<?php

namespace Modules\ProjectParticipant\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Illuminate\Http\Request;
use Modules\ProjectParticipant\Actions\AcShowDealer;
use Modules\ProjectParticipant\Entities\Dealer;

/**
 * Show dealer endpoint.
 */
final class EpShowDealer extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkInertia(
                'ProjectParticipant::Dealers/Show',
                new AcShowDealer(
                    Dealer::find($request->dealer)
                )
            )
        );
    }
}
