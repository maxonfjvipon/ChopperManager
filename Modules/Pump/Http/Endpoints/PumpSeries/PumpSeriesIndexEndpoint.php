<?php

namespace Modules\Pump\Http\Endpoints\PumpSeries;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use Illuminate\Contracts\Support\Responsable;
use Modules\Pump\Support\PumpSeries\PumpSeriesToShow;
use Symfony\Component\HttpFoundation\Response;

final class PumpSeriesIndexEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     */
    public function __invoke(): Responsable|Response
    {
        return (new TkAuthorized(
            'series_access',
            new TkAuthorized(
                'brand_access',
                new TkInertia(
                    "Pump::PumpSeries/Index",
                    new PumpSeriesToShow()
                )
            )
        ))->act();
    }
}
