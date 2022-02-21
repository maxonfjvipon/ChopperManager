<?php

namespace Modules\Project\Http\Endpoints;

use App\Takes\TkAuthorized;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Takes\TkAuthorizedProject;
use Modules\Project\Takes\TkDuplicatedProject;
use Modules\Project\Takes\TkRedirectedToProjectsIndex;
use Modules\Project\Entities\Project;
use Modules\Project\Http\Requests\CloneProjectRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects clone endpoint.
 * @package Modules\Project\Takes
 */
final class ProjectsCloneEndpoint extends Controller
{
    /**
     * @param CloneProjectRequest $request
     * @param Project $project
     * @return Responsable|Response
     */
    public function __invoke(CloneProjectRequest $request, Project $project): Responsable|Response
    {
        return (new TkAuthorizedProject(
            $project->id,
            new TkAuthorized(
                'project_clone',
                new TkDuplicatedProject(
                    $project,
                    new TkRedirectedToProjectsIndex()
                )
            )
        ))->act($request);
    }
}
