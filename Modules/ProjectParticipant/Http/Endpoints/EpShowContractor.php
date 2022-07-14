<?php

namespace Modules\ProjectParticipant\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Illuminate\Http\Request;
use Modules\ProjectParticipant\Actions\AcShowContractor;
use Modules\ProjectParticipant\Entities\Contractor;

/**
 * Show contractor endpoint.
 */
final class EpShowContractor extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkInertia(
                'ProjectParticipant::Contractors/Show',
                new AcShowContractor(Contractor::find($request->contractor))
            )
        );
    }
}
