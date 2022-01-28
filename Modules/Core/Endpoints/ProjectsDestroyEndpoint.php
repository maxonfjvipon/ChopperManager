<?php

namespace Modules\Core\Endpoints;

use App\Endpoints\AuthorizedEndpoint;
use App\Endpoints\RedirectBackEndpoint;
use App\Http\Controllers\Controller;
use App\Support\Renderable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Core\Endpoints\Deep\AuthorizedProjectEndpoint;
use Modules\Core\Endpoints\Deep\DeletedProjectEndpoint;
use Modules\Core\Entities\Project;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects destroy endpoint.
 * @package Modules\Core\Endpoints
 */
class ProjectsDestroyEndpoint extends Controller implements Renderable
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
            'project_delete',
            new AuthorizedProjectEndpoint(
                $this->project,
                new DeletedProjectEndpoint(
                    $this->project,
                    new RedirectBackEndpoint()
                )
            )
        ))->render($request);
    }
}
