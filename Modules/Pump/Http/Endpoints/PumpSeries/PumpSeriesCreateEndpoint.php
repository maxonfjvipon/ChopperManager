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
        return (new TkAuthorized(
            'series_create',
            new TkInertia(
                "Pump::PumpSeries/Create",
                new PumpSeriesProps()
            )
        ))->act();
    }
}
