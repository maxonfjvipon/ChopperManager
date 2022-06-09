<?php

namespace Modules\Pump\Http\Endpoints;

use App\Http\Controllers\Controller;
use Exception;
use Inertia\Response;
use Inertia\ResponseFactory;
use Modules\Pump\Actions\AcPumps;
use Modules\Pump\Http\Requests\RqLoadPumps;

/**
 * Pumps endpoint.
 */
final class EpPumps extends Controller
{
    /**
     * @param AcPumps $action
     * @return Response|ResponseFactory
     * @throws Exception
     */
    public function __invoke(AcPumps $action): Response|ResponseFactory
    {
        return inertia("Pump::Index", $action());
    }
}
