<?php

namespace Modules\Pump\Http\Endpoints\PumpSeries;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedBack;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Pump\Entities\PumpSeries;
use Symfony\Component\HttpFoundation\Response;

final class PumpSeriesRestoreEndpoint extends Controller
{
    /**
     * @param int $pumpSeriesId
     * @return Responsable|Response
     */
    public function __invoke(int $pumpSeriesId): Responsable|Response
    {
        return TkAuthorized::new(
            'series_restore',
            TkWithCallback::new(
                fn() => PumpSeries::withTrashed()->find($pumpSeriesId)->restore(),
                TkRedirectedBack::new()
            )
        )->act();
    }
}
