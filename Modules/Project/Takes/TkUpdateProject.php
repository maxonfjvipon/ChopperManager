<?php

namespace Modules\Project\Takes;

use App\Takes\Take;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Project\Entities\Project;
use Symfony\Component\HttpFoundation\Response;

/**
 * Endpoint that update the project from request.
 * @package Modules\Project\Takes\Deep
 */
final class TkUpdateProject implements Take
{
    /**
     * & Ctor.
     * @param Project $project
     * @param Take $origin
     */
    public function __construct(private Project $project, private Take $origin)
    {
    }

    /**
     * @inheritDoc
     */
    public function act(Request $request = null): Responsable|Response
    {
        return (new TkWithCallback(
            function () use ($request) {
                $this->project->update($request->validated());
                if (in_array($this->project->status_id, [3, 4])) {
                    if (!$this->project->trashed())
                        $this->project->delete();
                } else if ($this->project->trashed()) {
                    $this->project->restore();
                }
            },
            $this->origin
        ))->act($request);
    }
}
