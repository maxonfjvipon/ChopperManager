<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Illuminate\Http\Request;
use Modules\Selection\Actions\AcSelectionsDashboard;

/**
 * Selections dashboard endpoint.
 * @package Modules\Selection\Takes
 */
final class EpSelectionsDashboard extends TakeEndpoint
{
    /**
     * Ctor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkInertia(
                "Selection::Dashboard",
                new AcSelectionsDashboard($request->project)
            )
        );
    }
}
