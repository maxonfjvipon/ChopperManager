<?php

namespace Modules\Pump\Http\Endpoints\Pump;

use App\Http\Controllers\Controller;
use App\Takes\TkJson;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Auth;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Http\Requests\PumpShowRequest;
use Modules\Pump\Takes\TkCheckedPumpForUser;
use Modules\Pump\Transformers\Pumps\PumpToShow;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pumps show endpoint.
 */
final class PumpsShowEndpoint extends Controller
{
    /**
     * @param PumpShowRequest $request
     * @param Pump $pump
     * @return Responsable|Response
     * @throws \Exception
     */
    public function __invoke(PumpShowRequest $request, Pump $pump): Responsable|Response
    {
        return (new TkCheckedPumpForUser(
            $pump,
            Auth::user(),
            new TkJson(
                new PumpToShow($pump)
            )
        ))->act($request);
    }
}
