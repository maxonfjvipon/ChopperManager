<?php

namespace Modules\Selection\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedRoute;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Core\Takes\TkAuthorizedProject;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\SelectionRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Selections update endpoint.
 * @package Modules\Selection\Endpoints
 */
class SelectionsUpdateEndpoint extends Controller
{
    /**
     * @param SelectionRequest $request
     * @param Selection $selection
     * @return Responsable|Response
     */
    public function __invoke(SelectionRequest $request, Selection $selection): Responsable|Response
    {
        return TkAuthorizedProject::byId(
            $selection->project_id,
            TkAuthorized::new(
                'selection_edit',
                TkWithCallback::new(
                    fn() => $selection->update($request->validated()),
                    TkRedirectedRoute::new(
                        'projects.show',
                        $selection->project_id
                    )
                )
            )
        )->act($request);
    }
}
