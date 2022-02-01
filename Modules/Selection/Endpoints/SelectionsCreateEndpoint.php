<?php

namespace Modules\Selection\Endpoints;

use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrayableOf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Pump\Http\Requests\PumpableRequest;
use Modules\Selection\Takes\TkOptAuthorizedProject;
use Modules\Selection\Support\ArrSelectionProps;
use Modules\Selection\Support\TxtSelectionsCreateComponent;
use Symfony\Component\HttpFoundation\Response;

/**
 * Selections create endpoint.
 * @package Modules\Selection\Takes
 */
final class SelectionsCreateEndpoint extends Controller
{
    /**
     * @param PumpableRequest $request
     * @param string $project_id
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(PumpableRequest $request, string $project_id): Responsable|Response
    {
        return TkAuthorized::new(
            'selection_create',
            TkOptAuthorizedProject::new(
                $project_id,
                TkInertia::withTxtComponent(
                    TxtSelectionsCreateComponent::new($request)
                )->withArrayableProps(
                    ArrMerged::ofArrayables(
                        ArrayableOf::array(['project_id' => $project_id]),
                        ArrSelectionProps::new($request),
                    )
                ),
            )
        )->act($request);
    }
}
