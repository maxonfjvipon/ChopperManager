<?php

namespace Modules\Core\Endpoints;

use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Modules\Core\Takes\TkAuthorizedProject;
use Modules\Core\Entities\Project;
use Modules\Core\Transformers\EditProjectResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects edit endpoint.
 * @package Modules\Core\Takes
 */
final class ProjectsEditEndpoint extends Controller
{
    /**
     * @param Project $project
     * @return Responsable|Response
     */
    public function __invoke(Project $project): Responsable|Response
    {
        return TkAuthorized::new(
            'project_edit',
            TkAuthorizedProject::byProject(
                $project,
                TkInertia::new(
                    "Core::Projects/Edit",
                    fn() => ['project' => new EditProjectResource($project)]
                )
            )
        )->act();
    }
}
