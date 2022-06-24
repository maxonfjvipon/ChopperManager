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
use Symfony\Component\HttpFoundation\Response;

/**
 * Create project endpoint.
 */
final class EpCreateProject extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new TkInertia(
                'Project::Create',
                new AcEditOrCreateProject()
            )
        );
    }
}
