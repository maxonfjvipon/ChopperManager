<?php

namespace Modules\Selection\Http\Endpoints;

use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Request;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Project\Takes\TkAuthorizeProject;
use Modules\Pump\Http\Requests\RqPumpableType;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Support\TxtCreateSelectionComponent;
use Modules\Selection\Transformers\SelectionPropsResource;
use Modules\Selection\Transformers\SelectionResources\SelectionResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * Show selection endpoint.
 * @package Modules\Selection\Takes
 */
final class EpShowSelection extends Controller
{
    /**
     * @param RqPumpableType $request
     * @param int $selection_id
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(Request $request, int $selection_id): Responsable|Response
    {
        $selection = Selection::withOrWithoutTrashed()->findOrFail($selection_id);
        return (new TkInertia(
            new TxtCreateSelectionComponent(),
            new ArrMerged(
                ['project_id' => $selection->project_id],
                new SelectionPropsResource(),
                new SelectionResource($selection),
            )
        ))->act();
    }
}
