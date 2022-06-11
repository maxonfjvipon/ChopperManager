<?php

namespace Modules\Selection\Http\Endpoints;

use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Selection\Actions\AcCreateOrShowSelection;
use Modules\Selection\Http\Requests\RqDetermineSelection;
use Modules\Selection\Support\TxtCreateSelectionComponent;
use Modules\Selection\Tests\Feature\SelectionEndpointsTest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Create selection endpoint.
 * @see SelectionEndpointsTest::test_selections_create_endpoint()
 * @package Modules\Selection\Takes
 */
final class EpCreateSelection extends Controller
{
    /**
     * @param RqDetermineSelection $request
     * @param int $project_id
     * @param AcCreateOrShowSelection $action
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(
        RqDetermineSelection $request,
        int $project_id,
        AcCreateOrShowSelection $action
    ): Responsable|Response
    {
        return (new TkInertia(
            new TxtCreateSelectionComponent($request->station_type, $request->selection_type),
            $action($project_id, $request->station_type, $request->selection_type)
        ))->act();
    }
}
