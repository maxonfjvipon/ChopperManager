<?php

namespace Modules\Core\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Core\Support\ProjectsForStatistics;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects statistics endpoint.
 * @package Modules\Core\Takes
 */
final class ProjectsStatisticsEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(): Responsable|Response
    {
        return TkAuthorized::new(
            'project_statistics',
            TkInertia::new(
                "Core::Projects/Statistics",
                ProjectsForStatistics::new()
            )
        )->act();
    }
}
