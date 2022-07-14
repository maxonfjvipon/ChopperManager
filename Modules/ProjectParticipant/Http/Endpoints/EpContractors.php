<?php

namespace Modules\ProjectParticipant\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Modules\ProjectParticipant\Actions\AcContractors;

/**
 * Contractors endpoint.
 */
final class EpContractors extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new TkInertia(
                'ProjectParticipant::Contractors/Index',
                new AcContractors()
            )
        );
    }
}
