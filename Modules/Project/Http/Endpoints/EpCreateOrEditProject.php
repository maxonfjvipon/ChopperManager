<?php

namespace Modules\Project\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Http\Request;
use Modules\Project\Actions\AcCreateOrEditProject;
use Modules\Project\Entities\Project;

/**
 * Create or edit project endpoint.
 */
final class EpCreateOrEditProject extends TakeEndpoint
{
    /**
     * Ctor.
     *
     * @throws Exception
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkInertia(
                'Project::CreateOrEdit',
                new AcCreateOrEditProject(Project::find($request->project))
            ),
        );
    }
}
