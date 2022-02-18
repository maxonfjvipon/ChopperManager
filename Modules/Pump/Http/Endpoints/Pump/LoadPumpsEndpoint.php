<?php

namespace Modules\Pump\Http\Endpoints\Pump;

use App\Http\Controllers\Controller;
use App\Takes\TkJson;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Pump\Http\Requests\LoadPumpsRequest;
use Modules\Pump\Support\Pump\AvailablePumps;
use Modules\Pump\Support\Pump\FilteredPumps;
use Modules\Pump\Support\Pump\LoadedPumps;
use Modules\Pump\Support\Pump\LoadedPumpsAsArrayable;
use Modules\Pump\Support\Pump\MappedLoadedPumps\LoadedPumpsMapped;
use Symfony\Component\HttpFoundation\Response;

/**
 * Load pumps endpoint
 */
final class LoadPumpsEndpoint extends Controller
{
    /**
     * @var LoadedPumps $loadedPumps
     */
    private LoadedPumps $loadedPumps;

    /**
     * Ctor.
     * @param LoadedPumps $pumps
     */
    public function __construct(LoadedPumps $pumps)
    {
        $this->loadedPumps = $pumps;
    }

    /**
     * @param LoadPumpsRequest $request
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(LoadPumpsRequest $request): Responsable|Response
    {
        return TkJson::new(
            LoadedPumpsMapped::new(
                LoadedPumpsAsArrayable::new(
                    AvailablePumps::new(
                        FilteredPumps::new(
                            $this->loadedPumps,
                            $request->filter,
                        )
                    )
                ),
                $request
            )
        )->act($request);
    }
}
