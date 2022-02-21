<?php

namespace Modules\Pump\Http\Endpoints\PumpSeries;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use Illuminate\Contracts\Support\Responsable;
use Modules\Pump\Support\PumpSeries\PumpSeriesProps;
use Symfony\Component\HttpFoundation\Response;

final class PumpSeriesCreateEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     */
    public function __invoke(): Responsable|Response
    {
        return TkAuthorized::new(
            'series_create',
            TkInertia::new(
                "Pump::PumpSeries/Create",
                PumpSeriesProps::new()
            )
        )->act();
    }
}
