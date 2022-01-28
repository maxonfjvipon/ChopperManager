<?php

namespace Modules\Core\Endpoints;

use App\Endpoints\AuthorizedEndpoint;
use Modules\Core\Endpoints\Deep\AuthorizedProjectEndpoint;
use App\Endpoints\InertiaEndpoint;
use App\Http\Controllers\Controller;
use App\Support\Renderable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Core\Entities\Project;
use Modules\Core\Transformers\ShowProjectResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects show endpoint.
 * @package Modules\Core\Endpoints
 */
class ProjectsShowEndpoint extends Controller implements Renderable
{
    /**
     * @var Project $project
     */
    private Project $project;

    /**
     * @param Project $project
     * @return Responsable|Response
     * @throws AuthorizationException
     */
    public function __invoke(Project $project): Responsable|Response
    {
        $this->project = $project;
        return $this->render();
    }

    /**
     * @inheritDoc
     * @throws AuthorizationException
     */
    public function render(Request $request = null): Responsable|Response
    {
        return (new AuthorizedEndpoint(
            'project_show',
            new AuthorizedEndpoint(
                'selection_access',
                new AuthorizedProjectEndpoint(
                    $this->project,
                    new InertiaEndpoint("Core::Projects/Show", [
                        'project' => new ShowProjectResource($this->project)
                    ])
                )
            )
        ))->render($request);
    }
}
