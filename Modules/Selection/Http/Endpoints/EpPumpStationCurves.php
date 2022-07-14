<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkJson;
use Modules\Selection\Actions\AcPumpStationCurves;
use Modules\Selection\Http\Requests\RqPumpStationCurves;

/**
 * Selection curves endpoint.
 */
final class EpPumpStationCurves extends TakeEndpoint
{
    public function __construct(RqPumpStationCurves $request)
    {
        parent::__construct(
            new TkJson(
                new AcPumpStationCurves($request)
            )
        );
    }
}
