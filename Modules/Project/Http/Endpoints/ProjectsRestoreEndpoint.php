<?php

namespace Modules\Project\Http\Endpoints;

use App\Takes\TkAuthorized;
use App\Http\Controllers\Controller;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Entities\Project;
use Modules\Project\Takes\TkAuthorizedProject;
use Modules\Project\Takes\TkRedirectedToProjectsIndex;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects restore endpoint.
 * @package Modules\Project\Takes
 * @see Project booted method
 */
final class ProjectsRestoreEndpoint extends Controller
{
    /**
     * @param int $project_id
     * @return Responsable|Response
     */
    public function __invoke(int $project_id): Responsable|Response
    {
        return (new TkAuthorizedProject(
            $project_id,
            new TkAuthorized(
                'project_restore',
                new TkWithCallback(
                    fn() => Project::withTrashed()->find($project_id)->restore(),
                    new TkRedirectedToProjectsIndex()
                )
            )
        ))->act();
    }
}
