<?php

namespace Modules\ProjectParticipant\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Modules\ProjectParticipant\Actions\AcStoreDealer;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\ProjectParticipant\Http\Requests\RqStoreDealer;

/**
 * Store dealer endpoint.
 */
final class EpStoreDealer extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct(RqStoreDealer $request)
    {
        parent::__construct(
            new TkWithCallback(
                new AcStoreDealer($request),
                new TkRedirectToRoute('dealers.index')
            )
        );
    }
}
