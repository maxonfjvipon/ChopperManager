<?php

namespace Modules\Core\Http\Endpoints;

use App\Takes\TkAuthorized;
use App\Http\Controllers\Controller;
use App\Takes\TkRedirectedBack;
use Illuminate\Contracts\Support\Responsable;
use Modules\Core\Takes\TkAuthorizedProject;
use Modules\Core\Takes\TkRedirectedToProjectsIndex;
use Modules\Core\Takes\TkUpdatedProject;
use Modules\Core\Entities\Project;
use Modules\Core\Http\Requests\ProjectUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects update endpoint.
 * @package Modules\Core\Takes
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
        return TkAuthorized::new(
            'project_edit',
            TkAuthorizedProject::byId(
                $project_id,
                TkUpdatedProject::new(
                    Project::withTrashed()->find($project_id),
                    $request->has('name')
                        ? TkRedirectedToProjectsIndex::new()
                        : TkRedirectedBack::new()
                )
            )
        )->act($request);
    }
}
