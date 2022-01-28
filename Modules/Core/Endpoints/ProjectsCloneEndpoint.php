<?php

namespace Modules\Core\Endpoints;

use App\Endpoints\AuthorizedEndpoint;
use App\Http\Controllers\Controller;
use App\Support\Renderable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Core\Endpoints\Deep\AuthorizedProjectEndpoint;
use Modules\Core\Endpoints\Deep\ClonedProjectEndpoint;
use Modules\Core\Endpoints\Deep\RedirectToProjectsIndexRouteEndpoint;
use Modules\Core\Entities\Project;
use Modules\Core\Http\Requests\CloneProjectRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects clone endpoint.
 * @package Modules\Core\Endpoints
 */
class ProjectsCloneEndpoint extends Controller implements Renderable
{
    /**
     * @var Project $project
     */
    private Project $project;

    /**
     * @param CloneProjectRequest $request
     * @param Project $project
     * @return Responsable|Response
     * @throws AuthorizationException
     */
    public function __invoke(CloneProjectRequest $request, Project $project): Responsable|Response
    {
        $this->project = $project;
        return $this->render($request);
    }

    /**
     * @param Request|null $request
     * @return Responsable|Response
     * @throws AuthorizationException
     */
    public function render(Request $request = null): Responsable|Response
    {
        return (new AuthorizedProjectEndpoint(
            $this->project,
            new AuthorizedEndpoint(
                'project_clone',
                new ClonedProjectEndpoint(
                    $this->project,
                    new RedirectToProjectsIndexRouteEndpoint()
                )
            )
        ))->render($request);
    }
}
