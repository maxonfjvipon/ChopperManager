<?php

namespace Modules\Core\Http\Endpoints;

use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Modules\Core\Support\ProjectsToShow;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects index endpoint.
 * @package Modules\Core\Takes
 */
final class ProjectsIndexEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     */
    public function __invoke(): Responsable|Response
    {
        return TkAuthorized::new(
            'project_access',
            TkInertia::new(
                "Core::Projects/Index",
                ProjectsToShow::new()
            )
        )->act();
    }
}
