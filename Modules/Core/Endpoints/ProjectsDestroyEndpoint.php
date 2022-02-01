<?php

namespace Modules\Core\Endpoints;

use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedBack;
use App\Http\Controllers\Controller;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Core\Takes\TkAuthorizedProject;
use Modules\Core\Entities\Project;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects destroy endpoint.
 * @package Modules\Core\Takes
 */
final class ProjectsDestroyEndpoint extends Controller
{
    /**
     * @param Project $project
     * @return Responsable|Response
     */
    public function __invoke(Project $project): Responsable|Response
    {
        return TkAuthorized::new(
            'project_delete',
            TkAuthorizedProject::byProject(
                $project,
                TkWithCallback::new(
                    fn() => $project->delete(),
                    TkRedirectedBack::new()
                )
            )
        )->act();
    }
}
