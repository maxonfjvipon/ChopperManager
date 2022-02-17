<?php

namespace Modules\Core\Http\Endpoints;

use App\Takes\TkAuthorized;
use App\Http\Controllers\Controller;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Core\Takes\TkAuthorizedProject;
use Modules\Core\Takes\TkDuplicatedProject;
use Modules\Core\Takes\TkRedirectedToProjectsIndex;
use Modules\Core\Entities\Project;
use Modules\Core\Http\Requests\CloneProjectRequest;
use Modules\Core\Takes\TkUpdatedProject;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects clone endpoint.
 * @package Modules\Core\Takes
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
        return TkAuthorizedProject::byProject(
            $project,
            TkAuthorized::new(
                'project_clone',
                TkDuplicatedProject::new(
                    $project,
                    TkRedirectedToProjectsIndex::new()
                )
            )
        )->act($request);
    }
}
