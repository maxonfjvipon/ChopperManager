<?php

namespace Modules\Core\Endpoints\Deep;

use App\Endpoints\AuthorizedEndpoint;
use App\Support\Renderable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Core\Entities\Project;
use Symfony\Component\HttpFoundation\Response;

/**
 * Endpoint that checks for user access for specified project.
 * @package App\Endpoints
 */
class AuthorizedProjectEndpoint implements Renderable
{
    /**
     * @var Project
     */
    private Project $project;

    /**
     * @var Renderable
     */
    private Renderable $origin;

    /**
     * Ctor.
     * @param Project $project
     * @param Renderable $renderable
     */
    public function __construct(Project $project, Renderable $renderable)
    {
        $this->project = $project;
        $this->origin = $renderable;
    }

    /**
     * @inheritDoc
     * @throws AuthorizationException
     */
    public function render(Request $request = null): Responsable|Response
    {
        return (new AuthorizedEndpoint(
            'project_access_' . $this->project->id,
            $this->origin
        ))->render($request);
    }
}
