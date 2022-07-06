<?php

namespace Modules\Project\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkFromCallback;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Http\Request;
use Modules\Project\Actions\AcCreateOrEditProject;
use Modules\Project\Entities\Project;

/**
 * Create or edit project endpoint.
 * @package Modules\Project\Takes
 */
final class EpCreateOrEditProject extends TakeEndpoint
{
    /**
     * Ctor.
     * @param Request $request
     * @throws Exception
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkInertia(
                'Project::CreateOrEdit',
                new AcCreateOrEditProject(Project::find($request->project))
            ),
            $request
        );
    }
}
