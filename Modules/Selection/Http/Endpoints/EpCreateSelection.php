<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Exception;
use Modules\Selection\Actions\AcCreateOrShowSelection;
use Modules\Selection\Http\Requests\RqDetermineSelection;
use Modules\Selection\Support\TxtSelectionComponent;
use Modules\Selection\Tests\Feature\SelectionEndpointsTest;

/**
 * Create selection endpoint.
 *
 * @see SelectionEndpointsTest::test_selections_create_endpoint()
 */
final class EpCreateSelection extends TakeEndpoint
{
    /**
     * Ctor.
     *
     * @throws Exception
     */
    public function __construct(RqDetermineSelection $request)
    {
        parent::__construct(
            new TkInertia(
                new TxtSelectionComponent($request->station_type, $request->selection_type),
                new AcCreateOrShowSelection($request->project, $request->station_type, $request->selection_type)
            )
        );
    }
}
