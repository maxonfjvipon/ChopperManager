<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Http\Endpoints\TakeEndpoint;
use App\Interfaces\Take;
use App\Models\Enums\Country;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;
use Modules\PumpSeries\Actions\AcCreateOrEditPumpBrand;

/**
 * Pump brands create endpoint.
 */
final class EpCreatePumpBrand extends TakeEndpoint
{
    /**
     * Ctor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct(
            new TkInertia(
                "PumpSeries::PumpBrands/Create",
                new AcCreateOrEditPumpBrand()
            )
        );
    }
}
