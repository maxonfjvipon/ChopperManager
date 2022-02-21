<?php

namespace Modules\Selection\Http\Endpoints;

use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedBack;
use App\Takes\TkRedirectedWith;
use App\Http\Controllers\Controller;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Takes\TkAuthorizedProject;
use Modules\Project\Entities\Project;
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
        return (new TkAuthorizedProject(
            $project,
            new TkAuthorized(
                'selection_create',
                new TkWithCallback(
                    fn() => $project->selections()->create($request->validated()),
                    new TkRedirectedWith(
                        'success',
                        __('flash.selections.added'),
                        new TkRedirectedBack()
                    )
                )
            )
        ))->act($request);
    }
}
