<?php

namespace Modules\Selection\Http\Endpoints;

use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Project\Takes\TkAuthorizedProject;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Support\ArrSelectionProps;
use Modules\Selection\Support\ArrSelectionResource;
use Modules\Selection\Support\TxtSelectionsCreateComponent;
use Symfony\Component\HttpFoundation\Response;

/**
 * Selections show endpoint.
 * @package Modules\Selection\Takes
 */
final class SelectionsShowEndpoint extends Controller
{
    /**
     * @param Request $request
     * @param int $selection_id
     * @return Responsable|Response
     */
    public function __invoke(Request $request, int $selection_id): Responsable|Response
    {
        $selection = Selection::withOrWithoutTrashed()->findOrFail($selection_id);
        return (new TkAuthorizedProject(
            $selection->project_id,
            new TkAuthorized(
                'selection_show',
                new TkInertia(
                    new TxtSelectionsCreateComponent($request),
                    new ArrMerged(
                        ['project_id' => $selection->project_id],
                        new ArrSelectionProps(),
                        new ArrSelectionResource($selection)
                    )
                )
            )
        ))->act($request);
    }
}
