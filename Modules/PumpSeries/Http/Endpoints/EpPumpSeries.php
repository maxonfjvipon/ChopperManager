<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\PumpSeries\Actions\AcPumpSeries;
use Modules\PumpSeries\Support\PumpSeriesToShow;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pump series endpoint
 */
final class EpPumpSeries extends Controller
{
    /**
     * @param AcPumpSeries $action
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(AcPumpSeries $action): Responsable|Response
    {
        return (new TkInertia(
            "PumpSeries::Index",
            $action(),
        ))->act();
    }
}
