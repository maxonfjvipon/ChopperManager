<?php

namespace Modules\Selection\Endpoints;

use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Core\Takes\TkAuthorizedProject;
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
     * @param $selection_id
     * @return Responsable|Response
     */
    public function __invoke($selection_id): Responsable|Response
    {
        $selection = Selection::withOrWithoutTrashed()->findOrFail($selection_id);
        return TkAuthorizedProject::byId(
            $selection->project_id,
            TkAuthorized::new(
                'selection_show',
                TkInertia::new(
                    TxtSelectionsCreateComponent::new(),
                    ArrMerged::new(
                        ['project_id' => $selection->project_id],
                        ArrSelectionProps::new(),
                        ArrSelectionResource::new($selection)
                    )
                )
            )
        )->act();
    }
}
