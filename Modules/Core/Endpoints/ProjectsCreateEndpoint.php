<?php

namespace Modules\Core\Endpoints;

use App\Endpoints\AuthorizedEndpoint;
use App\Endpoints\InertiaEndpoint;
use App\Http\Controllers\Controller;
use App\Support\Renderable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects create endpoint
 * @package Modules\Core\Endpoints
 */
class ProjectsCreateEndpoint extends Controller implements Renderable
{
    /**
     * @return Responsable|Response
     * @throws AuthorizationException
     */
    public function __invoke(): Responsable|Response
    {
        return $this->render();
    }

    /**
     * @inheritDoc
     * @throws AuthorizationException
     */
    public function render(Request $request = null): Responsable|Response
    {
        return (new AuthorizedEndpoint(
            'project_create',
            new InertiaEndpoint('Core::Projects/Create')
        ))->render();
    }
}
