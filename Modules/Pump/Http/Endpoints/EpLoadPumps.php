<?php

namespace Modules\Pump\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkJson;
use Modules\Pump\Actions\AcLoadPumps;
use Modules\Pump\Http\Requests\RqLoadPumps;

/**
 * Load pumps endpoint.
 */
final class EpLoadPumps extends TakeEndpoint
{
    public function __construct(RqLoadPumps $request)
    {
        parent::__construct(
            new TkJson(new AcLoadPumps($request)),
            $request
        );
    }
}
