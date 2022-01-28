<?php

namespace Modules\Core\Endpoints\Deep;

use App\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Core\Entities\Project;
use Symfony\Component\HttpFoundation\Response;

/**
 * Endpoint that restores the project.
 * @package Modules\Core\Endpoints\Deep
 */
class RestoredProjectEndpoint implements Renderable
{
    /**
     * @var int $projectId
     */
    private int $projectId;

    /**
     * @var Renderable $origin
     */
    private Renderable $origin;

    /**
     * Ctor.
     * @param int $id
     * @param Renderable $renderable
     */
    public function __construct(int $id, Renderable $renderable)
    {
        $this->projectId = $id;
        $this->origin = $renderable;
    }

    /**
     * @inheritDoc
     */
    public function render(Request $request = null): Responsable|Response
    {
        Project::withTrashed()->find($this->projectId)->restore();
        return $this->origin->render($request);
    }
}
