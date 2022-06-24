<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Illuminate\Http\Request;
use Modules\PumpSeries\Actions\AcCreateOrEditPumpSeries;
use Modules\PumpSeries\Entities\PumpSeries;

/**
 * Edit pump series endpoint.
 */
final class EpEditPumpSeries extends TakeEndpoint
{
    /**
     * Ctor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkInertia(
                'PumpSeries::Edit',
                new AcCreateOrEditPumpSeries(PumpSeries::find($request->pump_series))
            )
        );
    }
}
