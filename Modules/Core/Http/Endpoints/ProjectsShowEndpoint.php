<?php

namespace Modules\Core\Http\Endpoints;

use App\Takes\TkAuthorized;
use Modules\Core\Support\ProjectToShow;
use Modules\Core\Takes\TkAuthorizedProject;
use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Modules\Core\Entities\Project;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects show endpoint.
 * @package Modules\Core\Takes
 */
final class ProjectsShowEndpoint extends Controller
{
    /**
     * @param $project_id
     * @return Responsable|Response
     */
    public function __invoke($project_id): Responsable|Response
    {
        $project = Project::withOrWithoutTrashed()->findOrFail($project_id);
        return TkAuthorized::new(
            'project_show',
            TkAuthorized::new(
                'selection_access',
                TkAuthorizedProject::byId(
                    $project_id,
                    TkInertia::new(
                        "Core::Projects/Show",
                        ProjectToShow::new($project)
                    )
                )
            )
        )->act();
    }
}
