<?php

namespace Modules\Core\Endpoints;

use App\Takes\TkAuthorized;
use App\Http\Controllers\Controller;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Core\Entities\Project;
use Modules\Core\Takes\TkAuthorizedProject;
use Modules\Core\Takes\TkRedirectedToProjectsIndex;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects restore endpoint.
 * @package Modules\Core\Takes
 */
final class ProjectsRestoreEndpoint extends Controller
{
    /**
     * @param int $project_id
     * @return Responsable|Response
     */
    public function __invoke(int $project_id): Responsable|Response
    {
        return TkAuthorizedProject::byId(
            $project_id,
            TkAuthorized::new(
                'project_restore',
                TkWithCallback::new(
                    fn() => Project::withTrashed()->find($project_id)->restore(),
                    TkRedirectedToProjectsIndex::new()
                )
            )
        )->act();
    }
}
