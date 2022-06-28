<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkJson;
use Exception;
use Modules\Selection\Actions\AcMakeSelection;
use Modules\Selection\Http\Requests\RqMakeSelection;

/**
 * Make selection endpoint.
 */
final class EpMakeSelection extends TakeEndpoint
{
    /**
     * Ctor.
     * @param RqMakeSelection $request
     * @throws Exception
     */
    public function __construct(RqMakeSelection $request)
    {
        ini_set('max_execution_time', '500');
        ini_set('memory_limit', '500M');
        parent::__construct(
            new TkJson(new AcMakeSelection($request))
        );
    }
}


