<?php

namespace Modules\Project\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Modules\Project\Actions\AcProjects;

/**
 * Projects endpoint.
 */
final class EpProjects extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct(
            new TkInertia(
                'Project::Index',
                new AcProjects()
            )
        );
    }
}
