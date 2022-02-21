<?php

namespace Modules\Pump\Http\Endpoints\PumpSeries;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use Illuminate\Contracts\Support\Responsable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Support\PumpSeries\PumpSeriesAsResource;
use Modules\Pump\Support\PumpSeries\PumpSeriesProps;
use Symfony\Component\HttpFoundation\Response;

final class PumpSeriesEditEndpoint extends Controller
{
    /**
     * @param PumpSeries $pumpSeries
     * @return Responsable|Response
     */
    public function __invoke(PumpSeries $pumpSeries): Responsable|Response
    {
        return TkAuthorized::new(
            "series_edit",
            TkInertia::new(
                "Pump::PumpSeries/Edit",
                ArrMerged::new(
                    PumpSeriesProps::new(),
                    PumpSeriesAsResource::new($pumpSeries)
                )
            )
        )->act();
    }
}
