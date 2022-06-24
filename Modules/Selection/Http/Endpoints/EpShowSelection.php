<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Http\Request;
use Modules\Selection\Actions\AcCreateOrShowSelection;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Support\TxtSelectionComponent;

/**
 * Show selection endpoint.
 * @package Modules\Selection\Takes
 */
final class EpShowSelection extends TakeEndpoint
{
    /**
     * Ctor.
     * @param Request $request
     * @throws Exception
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkInertia(
                new TxtSelectionComponent(
                    ($selection = Selection::find($request->selection))->station_type->key,
                    $selection->type->key,
                ),
                new AcCreateOrShowSelection(
                    $selection->project_id,
                    $selection->station_type->key,
                    $selection->type->key,
                    $selection
                )
            )
        );
    }
}
