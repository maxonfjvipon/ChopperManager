<?php

namespace Modules\Project\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Support\ProjectsForStatistics;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects statistics endpoint.
 */
final class EpProjectsStatistics extends Controller
{
    /**
     * @throws Exception
     */
    public function __invoke(): Responsable|Response
    {
        return (new TkInertia(
            'Project::Projects/Statistics',
            new ProjectsForStatistics()
        ))->act();
    }
}
