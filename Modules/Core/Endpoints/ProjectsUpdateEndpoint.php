<?php

namespace Modules\Core\Endpoints;

use App\Endpoints\AuthorizedEndpoint;
use App\Http\Controllers\Controller;
use App\Support\Renderable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Core\Endpoints\Deep\AuthorizedProjectEndpoint;
use Modules\Core\Endpoints\Deep\RedirectToProjectsIndexRouteEndpoint;
use Modules\Core\Endpoints\Deep\UpdatedProjectEndpoint;
use Modules\Core\Entities\Project;
use Modules\Core\Http\Requests\ProjectUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects update endpoint.
 * @package Modules\Core\Endpoints
 */
class ProjectsUpdateEndpoint extends Controller implements Renderable
{
    /**
     * @var Project $project
     */
    private Project $project;

    /**
     * @param ProjectUpdateRequest $request
     * @param Project $project
     * @return Responsable|Response
     * @throws AuthorizationException
     */
    public function __invoke(ProjectUpdateRequest $request, Project $project): Responsable|Response
    {
        $this->project = $project;
        return $this->render($request);
    }


    /**
     * @inheritDoc
     * @throws AuthorizationException
     */
    public function render(Request $request = null): Responsable|Response
    {
        return (new AuthorizedEndpoint(
            'project_edit',
            new AuthorizedProjectEndpoint(
                $this->project,
                new UpdatedProjectEndpoint(
                    $this->project,
                    new RedirectToProjectsIndexRouteEndpoint()
                )
            )
        ))->render($request);
    }
}
