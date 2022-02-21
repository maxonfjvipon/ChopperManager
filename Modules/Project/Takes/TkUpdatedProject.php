<?php

namespace Modules\Project\Takes;

use App\Support\Take;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Project\Entities\Project;
use Symfony\Component\HttpFoundation\Response;

/**
 * Endpoint that update the project from request.
 * @package Modules\Project\Takes\Deep
 */
final class TkUpdatedProject implements Take
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
     * Ctor wrap.
     * @param Project $project
     * @param Take $take
     * @return TkUpdatedProject
     */
    public static function new(Project $project, Take $take): TkUpdatedProject
    {
        return new self($project, $take);
    }

    /**
     * & Ctor.
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
        return (new TkWithCallback(
            function () use ($request) {
                $this->project->update($request->validated());
                if (in_array($this->project->status_id, [3, 4])) {
                    if (!$this->project->trashed())
                        $this->project->delete();
                } else
                    if ($this->project->trashed()) {
                        $this->project->restore();
                    }
            },
            $this->origin
        ))->act($request);
    }
}
