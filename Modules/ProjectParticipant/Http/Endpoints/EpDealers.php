<?php

namespace Modules\ProjectParticipant\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Modules\ProjectParticipant\Actions\AcDealers;

/**
 * Dealers endpoint.
 */
final class EpDealers extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new TkInertia(
                'ProjectParticipant::Dealers/Index',
                new AcDealers()
            )
        );
    }
}
