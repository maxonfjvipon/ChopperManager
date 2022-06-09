<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Selection\Actions\AcSelectionsDashboard;
use Symfony\Component\HttpFoundation\Response;

/**
 * Selection dashboard endpoint.
 * @package Modules\Selection\Takes
 */
final class EpSelectionsDashboard extends Controller
{
    /**
     * @param int $project_id
     * @param AcSelectionsDashboard $action
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(int $project_id, AcSelectionsDashboard $action): Responsable|Response
    {
        return (new TkInertia("Selection::Dashboard", $action($project_id)))->act();
    }
}
