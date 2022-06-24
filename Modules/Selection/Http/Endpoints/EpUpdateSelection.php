<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkWithCallback;
use Modules\Selection\Actions\AcUpdateSelection;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\RqStoreSelection;

/**
 * Update selection endpoint.
 * @package Modules\Selection\Http\Endpoints
 */
final class EpUpdateSelection extends TakeEndpoint
{
    /**
     * Ctor.
     * @param RqStoreSelection $request
     */
    public function __construct(RqStoreSelection $request)
    {
        parent::__construct(
            new TkWithCallback(
                new AcUpdateSelection($request, $selection = Selection::find($request->selection)),
                new TkRedirectToRoute(
                    'projects.show',
                    $selection->project_id
                )
            )
        );
    }
}
