<?php

namespace Modules\Core\Endpoints;

use App\Endpoints\AuthorizedEndpoint;
use App\Http\Controllers\Controller;
use App\Support\Renderable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Core\Endpoints\Deep\CreatedProjectEndpoint;
use Modules\Core\Endpoints\Deep\RedirectToProjectsIndexRouteEndpoint;
use Modules\Core\Http\Requests\ProjectStoreRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects store endpoint.
 * @package Modules\Core\Endpoints
 */
class ProjectsStoreEndpoint extends Controller implements Renderable
{
    /**
     * @param ProjectStoreRequest $request
     * @return Responsable|Response
     * @throws AuthorizationException
     */
    public function __invoke(ProjectStoreRequest $request): Responsable|Response
    {
        return $this->render($request);
    }

    /**
     * @param Request|null $request
     * @return Responsable|Response
     * @throws AuthorizationException
     */
    public function render(Request $request = null): Responsable|Response
    {
        return (new AuthorizedEndpoint(
            'project_create',
            new CreatedProjectEndpoint(
                new RedirectToProjectsIndexRouteEndpoint()
            )
        ))->render($request);
    }
}
