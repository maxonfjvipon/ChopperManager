<?php

namespace Modules\Project\Http\Endpoints;

use App\Takes\TkAuthorized;
use Modules\Project\Support\ProjectToShow;
use Modules\Project\Takes\TkAuthorizedProject;
use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Entities\Project;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects show endpoint.
 * @package Modules\Project\Takes
 */
final class ProjectsShowEndpoint extends Controller
{
    /**
     * @param $project_id
     * @return Responsable|Response
     */
    public function __invoke($project_id): Responsable|Response
    {
        return (new TkAuthorized(
            'project_show',
            new TkAuthorized(
                'selection_access',
                new TkAuthorizedProject(
                    $project_id,
                    TkInertia::new(
                        "Project::Projects/Show",
                        new ProjectToShow(
                            Project::withOrWithoutTrashed()->findOrFail($project_id)
                        )
                    )
                )
            )
        ))->act();
    }
}
