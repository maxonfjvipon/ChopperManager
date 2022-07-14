<?php

namespace Modules\Pump\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkJson;
use Modules\Pump\Actions\AcShowPump;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Http\Requests\RqShowPump;

/**
 * Show pump endpoint.
 */
final class EpShowPump extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct(RqShowPump $request)
    {
        parent::__construct(
            new TkJson(
                new AcShowPump(Pump::find($request->pump))
            ),
            $request
        );
    }
}
