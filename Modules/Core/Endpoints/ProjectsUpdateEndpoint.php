<?php

namespace Modules\Core\Endpoints;

use App\Takes\TkAuthorized;
use App\Http\Controllers\Controller;
use App\Support\Take;
use App\Takes\TkRedirectedBack;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Core\Takes\TkAuthorizedProject;
use Modules\Core\Takes\TkRedirectedToProjectsIndex;
use Modules\Core\Takes\TkUpdatedProject;
use Modules\Core\Entities\Project;
use Modules\Core\Http\Requests\ProjectUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects update endpoint.
 * @package Modules\Core\Takes
 */
final class ProjectsUpdateEndpoint extends Controller
{
    /**
     * @param ProjectUpdateRequest $request
     * @param Project $project
     * @return Responsable|Response
     */
    public function __invoke(ProjectUpdateRequest $request, Project $project): Responsable|Response
    {
        return TkAuthorized::new(
            'project_edit',
            TkAuthorizedProject::byProject(
                $project,
                TkUpdatedProject::new(
                    $project,
                    $request->has('name')
                        ? TkRedirectedToProjectsIndex::new()
                        : TkRedirectedBack::new()
                )
            )
        )->act($request);
    }
}
