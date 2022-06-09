<?php

namespace Modules\Pump\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkJson;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Pump\Actions\AcLoadPumps;
use Modules\Pump\Http\Requests\RqLoadPumps;
use Symfony\Component\HttpFoundation\Response;

/**
 * Load pumps endpoint
 */
final class EpLoadPumps extends Controller
{
    /**
     * @param RqLoadPumps $request
     * @param AcLoadPumps $action
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(RqLoadPumps $request, AcLoadPumps $action): Responsable|Response
    {
        return (new TkJson($action($request)))->act();
    }
}
