<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkJson;
use Exception;
use Modules\Selection\Actions\AcUpdatePumpStationCost;
use Modules\Selection\Http\Requests\RqDetermineSelection;

/**
 * Update pump station cost endpoint.
 */
final class EpUpdatePumpStationCost extends TakeEndpoint
{
    /**
     * Ctor.
     *
     * @param  RqDetermineSelection  $request
     *
     * @throws Exception
     */
    public function __construct(RqDetermineSelection $request)
    {
        parent::__construct(
            new TkJson(
                new AcUpdatePumpStationCost($request)
            )
        );
    }
}
