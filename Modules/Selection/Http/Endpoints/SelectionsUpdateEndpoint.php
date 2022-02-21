<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedRoute;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Takes\TkAuthorizedProject;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\SelectionRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Selections update endpoint.
 * @package Modules\Selection\Http\Endpoints
 */
final class SelectionsUpdateEndpoint extends Controller
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
                TkRedirectedRoute::new(
                    'projects.show',
                    $selection->updatedFrom($request->validated())->project_id
                )
            )
        )->act($request);
    }
}
