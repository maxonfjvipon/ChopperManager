<?php

namespace Modules\Project\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkFromCallback;
use App\Takes\TkInertia;
use Illuminate\Http\Request;
use Modules\Project\Actions\AcEditOrCreateProject;
use Modules\Project\Entities\Project;

/**
 * Edit project endpoint.
 * @package Modules\Project\Takes
 */
final class EpEditProject extends TakeEndpoint
{
    /**
     * Ctor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkFromCallback(
                fn() => new TkInertia(
                    'Project::Edit',
                    new AcEditOrCreateProject(Project::find($request->project))
                )
            ),
            $request
        );
    }
}
