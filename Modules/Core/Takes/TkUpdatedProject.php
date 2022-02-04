<?php

namespace Modules\Core\Takes;

use App\Support\Take;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Logical\Conjunction;
use Maxonfjvipon\Elegant_Elephant\Logical\Disjunction;
use Maxonfjvipon\Elegant_Elephant\Logical\EqualityOf;
use Maxonfjvipon\Elegant_Elephant\Logical\Negation;
use Modules\Core\Entities\Project;
use Symfony\Component\HttpFoundation\Response;

/**
 * Endpoint that update the project from request.
 * @package Modules\Core\Takes\Deep
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
    private function __construct(Project $project, Take $take)
    {
        $this->project = $project;
        $this->origin = $take;
    }

    /**
     * @inheritDoc
     */
    public function act(Request $request = null): Responsable|Response
    {
        return TkWithCallback::new(
            function () use ($request) {
                $this->project->update($request->validated());
                if (Disjunction::new(
                    EqualityOf::new($this->project->status_id, 4),
                    EqualityOf::new($this->project->status_id, 3)
                )->asBool()) {
                    if (!$this->project->trashed())
                        $this->project->delete();
                } else
                    if ($this->project->trashed()) {
                        $this->project->restore();
                    }
            },
            $this->origin
        )->act($request);
    }
}
