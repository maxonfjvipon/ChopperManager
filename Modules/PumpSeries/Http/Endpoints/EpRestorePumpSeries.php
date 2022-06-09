<?php

namespace Modules\PumpSeries\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorize;
use App\Takes\TkRedirectBack;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\PumpSeries\Entities\PumpSeries;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restore pump series endpoint.
 */
final class EpRestorePumpSeries extends Controller
{
    /**
     * @param int $pumpSeriesId
     * @return Responsable|Response
     */
    public function __invoke(int $pumpSeriesId): Responsable|Response
    {
        return (new TkWithCallback(
            fn() => PumpSeries::withTrashed()->find($pumpSeriesId)->restore(),
            new TkRedirectBack()
        ))->act();
    }
}
