<?php

namespace Modules\ProjectParticipant\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Http\Request;
use Modules\ProjectParticipant\Actions\AcCreateOrEditDealer;
use Modules\ProjectParticipant\Entities\Dealer;

/**
 * Create or edit dealer endpoint.
 */
final class EpCreateOrEditDealer extends TakeEndpoint
{
    /**
     * Ctor.
     *
     * @throws Exception
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkInertia(
                'ProjectParticipant::Dealers/CreateOrEdit',
                new AcCreateOrEditDealer(Dealer::find($request->dealer))
            )
        );
    }
}
