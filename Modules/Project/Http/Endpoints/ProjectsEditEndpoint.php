<?php

namespace Modules\Project\Http\Endpoints;

use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Takes\TkAuthorizedProject;
use Modules\Project\Entities\Project;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects edit endpoint.
 * @package Modules\Project\Takes
 */
final class ProjectsEditEndpoint extends Controller
{
    /**
     * @param Project $project
     * @return Responsable|Response
     */
    public function __invoke(Project $project): Responsable|Response
    {
        return (new TkAuthorized(
            'project_edit',
            new TkAuthorizedProject(
                $project->id,
                new TkInertia(
                    "Project::Projects/Edit",
                    [
                        'project' => [
                            'data' => [
                                'id' => $project->id,
                                'name' => $project->name
                            ]
                        ]
                    ]
                )
            )
        ))->act();
    }
}
