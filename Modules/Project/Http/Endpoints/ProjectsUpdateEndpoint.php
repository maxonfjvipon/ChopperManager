<?php

namespace Modules\Project\Http\Endpoints;

use App\Takes\TkAuthorized;
use App\Http\Controllers\Controller;
use App\Takes\TkRedirectedBack;
use App\Takes\TkTernary;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Takes\TkAuthorizedProject;
use Modules\Project\Takes\TkRedirectedToProjectsIndex;
use Modules\Project\Takes\TkUpdatedProject;
use Modules\Project\Entities\Project;
use Modules\Project\Http\Requests\ProjectUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects update endpoint.
 * @package Modules\Project\Takes
 * @see Project booted method
 */
final class ProjectsUpdateEndpoint extends Controller
{
    /**
     * @param ProjectUpdateRequest $request
     * @param $project_id
     * @return Responsable|Response
     */
    public function __invoke(ProjectUpdateRequest $request, $project_id): Responsable|Response
    {
        return (new TkAuthorized(
            'project_edit',
            new TkAuthorizedProject(
                $project_id,
                new TkUpdatedProject(
                    Project::withTrashed()->find($project_id),
                    new TkTernary(
                        $request->has('name'),
                        new TkRedirectedToProjectsIndex(),
                        new TkRedirectedBack()
                    )
                )
            )
        ))->act($request);
    }
}
