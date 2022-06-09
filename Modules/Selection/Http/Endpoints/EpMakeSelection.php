<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorize;
use App\Takes\TkJson;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Selection\Http\Requests\RqMakeSelection;
use Modules\Selection\Support\SelectedPumps\SelectedPumps;
use Symfony\Component\HttpFoundation\Response;

/**
 * Make selection endpoint.
 */
final class EpMakeSelection extends Controller
{
    /**
     * @param RqMakeSelection $request
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(RqMakeSelection $request): Responsable|Response
    {
        return (new TkJson(
            new SelectedPumps($request)
        ))->act($request);
    }
}


