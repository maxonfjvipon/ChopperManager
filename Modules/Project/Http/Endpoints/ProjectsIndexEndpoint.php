<?php

namespace Modules\Project\Http\Endpoints;

use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Support\ProjectsToShow;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects index endpoint.
 * @package Modules\Project\Takes
 */
final class ProjectsIndexEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     */
    public function __invoke(): Responsable|Response
    {
        return (new TkAuthorized(
            'project_access',
            new TkInertia(
                "Project::Projects/Index",
                new ProjectsToShow(Auth::user())
            )
        ))->act();
    }
}
