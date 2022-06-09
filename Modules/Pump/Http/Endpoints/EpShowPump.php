<?php

namespace Modules\Pump\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkJson;
use App\Takes\TkWithCallback;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Auth;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Http\Requests\RqShowPump;
use Modules\Pump\Takes\TkCheckIfPumpIsAvailableForUser;
use Modules\Pump\Transformers\RcPumpToShow;
use Symfony\Component\HttpFoundation\Response;

/**
 * Show pump endpoint.
 */
final class EpShowPump extends Controller
{
    /**
     * @param RqShowPump $request
     * @param Pump $pump
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(RqShowPump $request, Pump $pump): Responsable|Response
    {
        return \response()->json([
            'pump' => new RcPumpToShow($pump)
        ]);
    }
}
