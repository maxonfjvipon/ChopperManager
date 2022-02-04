<?php

namespace Modules\Selection\Endpoints;

use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedBack;
use App\Takes\TkRedirectedWith;
use App\Http\Controllers\Controller;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Core\Takes\TkAuthorizedProject;
use Modules\Core\Entities\Project;
use Modules\Selection\Http\Requests\SelectionRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Selections store endpoint.
 * @package Modules\Selection\Takes
 */
final class SelectionsStoreEndpoint extends Controller
{
    /**
     * @param SelectionRequest $request
     * @param Project $project
     * @return Responsable|Response
     */
    public function __invoke(SelectionRequest $request, Project $project): Responsable|Response
    {
        return TkAuthorizedProject::byProject(
            $project,
            TkAuthorized::new(
                'selection_create',
                TkWithCallback::new(
                    fn() => $project->selections()->create($request->validated()),
                    TkRedirectedWith::new(
                        'success',
                        __('flash.selections.added'),
                        TkRedirectedBack::new()
                    )
                )
            )
        )->act($request);
    }
}