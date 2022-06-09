<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorize;
use App\Takes\TkJson;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Selection\Entities\PumpStation;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\RqPumpStationCurves;
use Modules\Selection\Support\PumpStationCurves;
use Symfony\Component\HttpFoundation\Response;

/**
 * Selection curves endpoint.
 * @package Modules\Selection\Http\Endpoints
 */
final class EpPumpStationCurves extends Controller
{
    /**
     * @param RqPumpStationCurves $request
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(RqPumpStationCurves $request): Responsable|Response
    {
        return (new TkJson(
            new PumpStationCurves(
                new PumpStation($request->validated())
            )
        ))->act($request);
    }
}
