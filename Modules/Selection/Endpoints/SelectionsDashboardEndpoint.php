<?php

namespace Modules\Selection\Endpoints;

use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use App\Support\Take;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\AdminPanel\Support\ArrTenantSelectionTypes;
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
        return TkInertia::withStrComponent("Selection::Dashboard")
            ->withArrayProps([
                'project_id' => $project_id,
                'selection_types' => ArrMapped::ofArrayable(
                    ArrTenantSelectionTypes::new(),
                    fn(SelectionType $type) => [
                        'name' => $type->name,
                        'pumpable_type' => $type->pumpable_type,
                        'img' => $type->imgForTenant()
                    ]
                )->asArray()
            ])->act();
    }
}
