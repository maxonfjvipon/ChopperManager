<?php

namespace Modules\Project\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Illuminate\Http\Request;
use Modules\Project\Actions\AcShowProject;
use Modules\Project\Entities\Project;

/**
 * Show project endpoint.
 */
final class EpShowProject extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkInertia(
                'Project::Show',
                new AcShowProject(Project::find($request->project))
            ),
        );
    }
}
