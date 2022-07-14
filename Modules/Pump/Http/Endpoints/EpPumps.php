<?php

namespace Modules\Pump\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Modules\Pump\Actions\AcPumps;

/**
 * Pumps endpoint.
 */
final class EpPumps extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new TkInertia('Pump::Index', new AcPumps())
        );
    }
}
