<?php

namespace Modules\Core\Takes;

use App\Support\Take;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
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
            fn() => $this->project->update($request->validated()),
            $this->origin
        )->act($request);
    }
}
