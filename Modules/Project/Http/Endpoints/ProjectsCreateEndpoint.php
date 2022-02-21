<?php

namespace Modules\Project\Http\Endpoints;

use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects create endpoint
 * @package Modules\Project\Takes
 */
final class ProjectsCreateEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     */
    public function __invoke(): Responsable|Response
    {
        return (new TkAuthorized(
            'project_create',
            new TkInertia('Project::Projects/Create')
        ))->act();
    }
}
