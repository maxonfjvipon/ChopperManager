<?php

namespace Modules\Pump\Http\Endpoints\Pump;

use App\Http\Controllers\Controller;
use App\Takes\TkJson;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Pump\Http\Requests\LoadPumpsRequest;
use Modules\Pump\Support\Pump\LazyLoadedPumps\LazyLoadedPumps;
use Modules\Pump\Support\Pump\LazyLoadedPumps\LzLdAvailablePumps;
use Modules\Pump\Support\Pump\LoadedPumps\FilteredPumps;
use Modules\Pump\Support\Pump\LoadedPumps\LoadedPumpsAsArrayable;
use Modules\Pump\Support\Pump\LoadedPumps\MappedLoadedPumps\LoadedPumpsMapped;
use Symfony\Component\HttpFoundation\Response;

/**
 * Load pumps endpoint
 */
final class LoadPumpsEndpoint extends Controller
{
    /**
     * @var LazyLoadedPumps $loadedPumps
     */
    private LazyLoadedPumps $loadedPumps;

    /**
     * Ctor.
     * @param LazyLoadedPumps $pumps
     */
    public function __construct(LazyLoadedPumps $pumps)
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
        return (new TkJson(
            new LoadedPumpsMapped(
                new LoadedPumpsAsArrayable(
                    new LzLdAvailablePumps(
                        new FilteredPumps(
                            $this->loadedPumps,
                            $request->filter,
                        )
                    )
                ),
                $request
            )
        ))->act($request);
    }
}
