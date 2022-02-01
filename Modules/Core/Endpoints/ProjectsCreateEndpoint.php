<?php

namespace Modules\Core\Endpoints;

use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use App\Support\Take;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects create endpoint
 * @package Modules\Core\Takes
 */
final class ProjectsCreateEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     * @throws AuthorizationException
     */
    public function __invoke(): Responsable|Response
    {
        return TkAuthorized::new(
            'project_create',
            TkInertia::withStrComponent('Core::Projects/Create')
        )->act();
    }
}
