<?php

namespace Modules\ProjectParticipant\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Modules\ProjectParticipant\Actions\AcUpdateDealer;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\ProjectParticipant\Http\Requests\RqUpdateDealer;

/**
 * Update dealer endpoint.
 */
final class EpUpdateDealer extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct(RqUpdateDealer $request)
    {
        parent::__construct(
            new TkWithCallback(
                new AcUpdateDealer($request),
                new TkRedirectToRoute('dealers.index')
            )
        );
    }
}
