<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkJson;
use Illuminate\Contracts\Support\Responsable;
use Modules\Selection\Http\Requests\MakeSelectionRequest;
use Modules\Selection\Support\SelectedPumps\SelectedPumps;
use Symfony\Component\HttpFoundation\Response;

/**
 * Select pumps endpoint.
 */
final class SelectionsSelectEndpoint extends Controller
{
    /**
     * @param MakeSelectionRequest $request
     * @return Responsable|Response
     */
    public function __invoke(MakeSelectionRequest $request): Responsable|Response
    {
        return (new TkAuthorized(
            'selection_create',
            new TkAuthorized(
                'selection_edit',
                new TkJson(
                    new SelectedPumps($request)
                )
            )
        ))->act($request);
    }
}


