<?php

namespace Modules\Project\Takes;

use App\Support\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Project\Entities\Project;
use Symfony\Component\HttpFoundation\Response;

/**
 * Take that duplicates project
 */
final class TkDuplicatedProject implements Take
{
    /**
     * @var Project $project
     */
    private Project $project;

    /**
     * @var Take $origin
     */
    private Take $origin;

    /**
     * Ctor.
     * @param Project $project
     * @param Take $take
     */
    public function __construct(Project $project, Take $take)
    {
        $this->project = $project;
        $this->origin = $take;
    }

    /**
     * @inheritDoc
     */
    public function act(Request $request = null): Responsable|Response
    {
        return (new TkUpdatedProject(
            $this->project->duplicate(),
            $this->origin
        ))->act($request);
    }
}
