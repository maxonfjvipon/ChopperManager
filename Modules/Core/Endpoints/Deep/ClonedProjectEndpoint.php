<?php

namespace Modules\Core\Endpoints\Deep;

use App\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Core\Entities\Project;
use Symfony\Component\HttpFoundation\Response;

/**
 * Endpoint that clones the project.
 * @package Modules\Core\Endpoints\Deep
 */
class ClonedProjectEndpoint implements Renderable
{
    /**
     * @var Project $project
     */
    private Project $project;

    /**
     * @var Renderable $origin
     */
    private Renderable $origin;

    /**
    & Ctor.
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
     */
    public function render(Request $request = null): Responsable|Response
    {
        $clone = $this->project->duplicate();
        return (new UpdatedProjectEndpoint(
            $clone,
            $this->origin
        ))->render($request);
    }
}
