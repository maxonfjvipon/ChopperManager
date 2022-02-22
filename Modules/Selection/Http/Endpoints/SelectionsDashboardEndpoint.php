<?php

namespace Modules\Selection\Http\Endpoints;

use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Auth;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Selection\Entities\SelectionType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Selection dashboard endpoint.
 * @package Modules\Selection\Takes
 */
final class SelectionsDashboardEndpoint extends Controller
{
    /**
     * @param $project_id
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke($project_id): Responsable|Response
    {
        return (new TkInertia(
            "Selection::Dashboard",
            new ArrMerged(
                ['project_id' => $project_id],
                new ArrObject(
                    "selection_types",
                    new ArrMapped(
                        [...Auth::user()->available_selection_types],
                        fn(SelectionType $type) => [
                            'name' => $type->name,
                            'pumpable_type' => $type->pumpable_type,
                            'img' => $type->img
                        ]
                    )
                )
            )
        ))->act();
    }
}
