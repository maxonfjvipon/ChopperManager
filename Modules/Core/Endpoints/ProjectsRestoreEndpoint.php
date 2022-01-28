<?php

namespace Modules\Core\Endpoints;

use App\Endpoints\AuthorizedEndpoint;
use App\Http\Controllers\Controller;
use App\Support\Renderable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Core\Endpoints\Deep\RedirectToProjectsIndexRouteEndpoint;
use Modules\Core\Endpoints\Deep\RestoredProjectEndpoint;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects restore endpoint.
 * @package Modules\Core\Endpoints
 */
class ProjectsRestoreEndpoint extends Controller implements Renderable
{
    /**
     * @var int $id
     */
    private int $projectId;

    /**
     * @param int $id
     * @return Responsable|Response
     * @throws AuthorizationException
     */
    public function __invoke(int $id): Responsable|Response
    {
        $this->projectId = $id;
        return $this->render();
    }

    /**
     * @inheritDoc
     * @throws AuthorizationException
     */
    public function render(Request $request = null): Responsable|Response
    {
        return (new AuthorizedEndpoint(
            'project_access_' . $this->projectId,
            new AuthorizedEndpoint(
                'project_restore',
                new RestoredProjectEndpoint(
                    $this->projectId,
                    new RedirectToProjectsIndexRouteEndpoint()
                )
            )
        ))->render($request);
    }
}
