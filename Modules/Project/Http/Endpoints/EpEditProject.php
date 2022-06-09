<?php

namespace Modules\Project\Http\Endpoints;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Actions\AcProjectData;
use Modules\Project\Entities\Project;
use Modules\Project\Transformers\RcProjectToEdit;
use Symfony\Component\HttpFoundation\Response;

/**
 * Edit project endpoint.
 * @package Modules\Project\Takes
 */
final class EpEditProject extends Controller
{
    /**
     * @param Project $project
     * @param AcProjectData $action
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(Project $project, AcProjectData $action): Responsable|Response
    {
        return inertia(
            "Project::Edit",
            array_merge($action(), ['project' => new RcProjectToEdit($project)])
        );
    }
}
