<?php

namespace Modules\Pump\Http\Endpoints\Pump;

use App\Http\Controllers\Controller;
use App\Takes\TkJson;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\Pump\Http\Requests\LoadPumpsRequest;
use Modules\Pump\Support\Pump\LoadedPumps;
use Symfony\Component\HttpFoundation\Response;

/**
 * Load pumps endpoint
 */
final class LoadPumpsEndpoint extends Controller
{

    /**
     * @param LoadPumpsRequest $request
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(LoadPumpsRequest $request): Responsable|Response
    {
        return TkJson::new(
            LoadedPumps::new($request)
        )->act($request);
    }
}
