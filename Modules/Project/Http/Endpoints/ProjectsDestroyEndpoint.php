<?php

namespace Modules\Project\Http\Endpoints;

use App\Takes\TkAuthorized;
use App\Takes\TkRedirectedBack;
use App\Http\Controllers\Controller;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Takes\TkAuthorizedProject;
use Modules\Project\Entities\Project;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects destroy endpoint.
 * @package Modules\Project\Takes
 * @see Project booted method
 */
final class ProjectsDestroyEndpoint extends Controller
{
    /**
     * @param Project $project
     * @return Responsable|Response
     */
    public function __invoke(Project $project): Responsable|Response
    {
        return (new TkAuthorized(
            'project_delete',
            new TkAuthorizedProject(
                $project->id,
                new TkWithCallback(
                    fn() => $project->delete(),
                    new TkRedirectedBack()
                )
            )
        ))->act();
    }
}
