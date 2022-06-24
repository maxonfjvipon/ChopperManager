<?php

namespace Modules\Project\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkWithCallback;
use Modules\Project\Entities\Project;
use Modules\Project\Http\Requests\RqUpdateProject;
use Modules\Project\Takes\TkRedirectToProjectsIndex;

/**
 * Update project endpoint.
 * @package Modules\Project\Takes
 */
final class EpUpdateProject extends TakeEndpoint
{
    /**
     * Ctor.
     * @param RqUpdateProject $request
     */
    public function __construct(RqUpdateProject $request)
    {
        parent::__construct(
            new TkWithCallback(
                fn() => Project::find($request->project)->update($request->projectProps()),
                new TkRedirectToProjectsIndex()
            ),
            $request
        );
    }
}
