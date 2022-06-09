<?php

namespace Modules\Project\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Actions\AcProjects;
use Symfony\Component\HttpFoundation\Response;

final class EpProjects extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param AcProjects $action
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(AcProjects $action): Responsable|Response
    {
        return (new TkInertia('Project::Index', $action()))->act();
    }
}
