<?php

namespace Modules\Core\Endpoints;

use App\Takes\TkAuthorized;
use Modules\Core\Takes\TkAuthorizedProject;
use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Modules\Core\Entities\Project;
use Modules\Core\Transformers\ShowProjectResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects show endpoint.
 * @package Modules\Core\Takes
 */
final class ProjectsShowEndpoint extends Controller
{
    /**
     * @param Project $project
     * @return Responsable|Response
     */
    public function __invoke(Project $project): Responsable|Response
    {
        return TkAuthorized::new(
            'project_show',
            TkAuthorized::new(
                'selection_access',
                TkAuthorizedProject::byProject(
                    $project,
                    TkInertia::new(
                        "Core::Projects/Show",
                        fn() => ['project' => new ShowProjectResource($project)])
                )
            )
        )->act();
    }
}
