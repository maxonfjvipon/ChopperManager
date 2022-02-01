<?php

namespace Modules\Selection\Endpoints;

use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use App\Support\Take;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrayableOf;
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
     * @param Selection $selection
     * @return Responsable|Response
     * @throws AuthorizationException
     * @throws Exception
     */
    public function __invoke(Selection $selection): Responsable|Response
    {
        return TkAuthorizedProject::byId(
            $selection->project_id,
            TkAuthorized::new(
                'selection_show',
                TkInertia::withTxtComponent(TxtSelectionsCreateComponent::new())
                    ->withArrayableProps(
                        ArrMerged::ofArrayables(
                            ArrayableOf::array(['project_id' => $selection->project_id]),
                            ArrSelectionProps::new(),
                            ArrSelectionResource::new($selection)
                        )
                    )
            )
        )->act();
    }
}
