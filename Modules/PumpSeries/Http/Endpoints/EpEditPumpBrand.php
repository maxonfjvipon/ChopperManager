<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Http\Request;
use Modules\PumpSeries\Actions\AcCreateOrEditPumpBrand;
use Modules\PumpSeries\Entities\PumpBrand;

/**
 * Edit pump brand endpoint.
 */
final class EpEditPumpBrand extends TakeEndpoint
{
    /**
     * Ctor.
     * @param Request $request
     * @throws Exception
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkInertia(
                'PumpSeries::PumpBrands/Edit',
                new AcCreateOrEditPumpBrand(PumpBrand::find($request->pump_brand))
            )
        );
    }
}
