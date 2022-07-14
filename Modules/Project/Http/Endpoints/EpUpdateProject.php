<?php

namespace Modules\Project\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkWithCallback;
use Modules\Project\Actions\AcUpdateProject;
use Modules\Project\Entities\Project;
use Modules\Project\Http\Requests\RqUpdateProject;
use Modules\Project\Takes\TkRedirectToProjectsIndex;

/**
 * Update project endpoint.
 */
final class EpUpdateProject extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct(RqUpdateProject $request)
    {
        parent::__construct(
            new TkWithCallback(
                new AcUpdateProject($request),
                new TkRedirectToProjectsIndex()
            ),
            $request
        );
    }
}
