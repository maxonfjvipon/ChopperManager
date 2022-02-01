<?php

namespace Modules\Selection\Endpoints;

use App\Http\Controllers\Controller;
use App\Support\TxtView;
use App\Takes\TkAuthorized;
use App\Takes\TkJson;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\CurvesForSelectionRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Selections curves endpoint.
 * @package Modules\Selection\Endpoints
 */
class SelectionsCurvesEndpoint extends Controller
{
    /**
     * @param CurvesForSelectionRequest $request
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(CurvesForSelectionRequest $request): Responsable|Response
    {
        return TkAuthorized::new(
            'selection_create',
            TkAuthorized::new(
                'selection_edit',
                TkJson::new(
                    fn() => TxtView::new(
                        'selection::selection_perf_curves',
                        (new Selection($request->validated()))
                            ->withCurves()
                            ->curves_data
                    )->asString()
                )
            )
        )->act($request);
    }
}
