<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Exception;
use Modules\PumpSeries\Actions\AcCreateOrEditPumpBrand;

/**
 * Pump brands create endpoint.
 */
final class EpCreatePumpBrand extends TakeEndpoint
{
    /**
     * Ctor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct(
            new TkInertia(
                'PumpSeries::PumpBrands/Create',
                new AcCreateOrEditPumpBrand()
            )
        );
    }
}
