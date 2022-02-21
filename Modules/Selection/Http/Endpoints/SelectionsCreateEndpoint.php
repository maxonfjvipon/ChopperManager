<?php

namespace Modules\Selection\Http\Endpoints;

use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Support\Responsable;
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
        return (new TkAuthorized(
            'selection_create',
            new TkOptAuthorizedProject(
                $project_id,
                new TkInertia(
                    new TxtSelectionsCreateComponent($request),
                    new ArrMerged(
                        ['project_id' => $project_id],
                        new ArrSelectionProps($request),
                    )
                ),
            )
        ))->act($request);
    }
}
