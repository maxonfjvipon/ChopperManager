<?php

namespace Modules\Project\Http\Endpoints;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Project\Actions\AcProjectData;
use Symfony\Component\HttpFoundation\Response;

final class EpCreateProject extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param AcProjectData $action
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(AcProjectData $action): Responsable|Response
    {
        return inertia("Project::Create", $action());
    }
}
