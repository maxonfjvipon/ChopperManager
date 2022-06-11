<?php

namespace Modules\Selection\Http\Endpoints;

use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Selection\Actions\AcCreateOrShowSelection;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Support\TxtCreateSelectionComponent;
use Modules\Selection\Transformers\SelectionResources\SelectionAsResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * Show selection endpoint.
 * @package Modules\Selection\Takes
 */
final class EpShowSelection extends Controller
{
    /**
     * @param Selection $selection
     * @param AcCreateOrShowSelection $action
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(Selection $selection, AcCreateOrShowSelection $action): Responsable|Response
    {
        return (new TkInertia(
            new TxtCreateSelectionComponent($selection->station_type->key, $selection->type->key),
            $action($selection->project_id, $selection->station_type->key, $selection->type->key, $selection),
        ))->act();
    }
}
