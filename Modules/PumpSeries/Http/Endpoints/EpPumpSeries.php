<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Modules\PumpSeries\Actions\AcPumpSeries;

/**
 * Pump series endpoint.
 */
final class EpPumpSeries extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new TkInertia(
                'PumpSeries::Index',
                new AcPumpSeries()
            )
        );
    }
}
