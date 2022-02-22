<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkJson;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\CurvesForSelectionRequest;
use Modules\Selection\Support\TxtSelectionCurvesView;
use Symfony\Component\HttpFoundation\Response;

/**
 * Selections curves endpoint.
 * @package Modules\Selection\Http\Endpoints
 */
final class SelectionsCurvesEndpoint extends Controller
{
    /**
     * @param CurvesForSelectionRequest $request
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(CurvesForSelectionRequest $request): Responsable|Response
    {
        return (new TkAuthorized(
            'selection_create',
            new TkAuthorized(
                'selection_edit',
                new TkJson(
                    new TxtSelectionCurvesView(
                        new Selection($request->validated())
                    )
                )
            )
        ))->act($request);
    }
}
