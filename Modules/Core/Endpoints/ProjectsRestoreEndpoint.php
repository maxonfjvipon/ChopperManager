<?php

namespace Modules\Core\Endpoints;

use App\Takes\TkAuthorized;
use App\Http\Controllers\Controller;
use App\Support\Take;
use App\Takes\TkWithCallback;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Core\Entities\Project;
use Modules\Core\Takes\TkAuthorizedProject;
use Modules\Core\Takes\TkRedirectedToProjectsIndex;
use Modules\Core\Takes\TkRestoredProject;
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
     * @throws AuthorizationException
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
