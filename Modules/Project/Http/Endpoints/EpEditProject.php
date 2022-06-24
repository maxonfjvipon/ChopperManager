<?php

namespace Modules\Project\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Http\Endpoints\TakeEndpoint;
use App\Interfaces\Take;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Project\Actions\AcEditOrCreateProject;
use Modules\Project\Entities\Project;
use Modules\Project\Transformers\RcProjectToEdit;
use Symfony\Component\HttpFoundation\Response;

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
            new TkInertia(
                'Project::Edit',
                new AcEditOrCreateProject(Project::find($request->project))
            ),
            $request
        );
    }
}
