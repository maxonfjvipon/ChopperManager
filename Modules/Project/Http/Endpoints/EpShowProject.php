<?php

namespace Modules\Project\Http\Endpoints;

use App\Takes\TkInertia;
use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Actions\AcShowProject;
use Modules\Project\Entities\Project;
use Symfony\Component\HttpFoundation\Response;

/**
 * Show project endpoint.
 * @package Modules\Project\Takes
 */
final class EpShowProject extends Controller
{
    /**
     * @param Project $project
     * @param AcShowProject $action
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(Project $project, AcShowProject $action): Responsable|Response
    {
        return (new TkInertia('Project::Show', $action($project)))->act();
    }
}
