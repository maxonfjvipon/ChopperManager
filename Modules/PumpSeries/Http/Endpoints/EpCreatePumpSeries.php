<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Modules\PumpSeries\Actions\AcCreateOrEditPumpSeries;

/**
 * Create pump series endpoint.
 */
final class EpCreatePumpSeries extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new TkInertia('PumpSeries::Create', new AcCreateOrEditPumpSeries()),
        );
    }
}
