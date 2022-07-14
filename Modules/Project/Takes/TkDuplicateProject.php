<?php

namespace Modules\Project\Takes;

use App\Interfaces\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Project\Entities\Project;
use Symfony\Component\HttpFoundation\Response;

/**
 * Take that duplicates project.
 */
final class TkDuplicateProject implements Take
{
    /**
     * Ctor.
     */
    public function __construct(private Project $project, private Take $origin)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function act(Request $request = null): Responsable|Response
    {
        return (new TkUpdateProject(
            $this->project->duplicate(),
            $this->origin
        ))->act($request);
    }
}
