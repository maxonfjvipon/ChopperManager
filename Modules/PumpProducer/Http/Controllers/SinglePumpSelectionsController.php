<?php

namespace Modules\PumpProducer\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\AdminPanel\Entities\Tenant;

class SinglePumpSelectionsController extends \Modules\Selection\Http\Controllers\SinglePumpSelectionsController
{
    /**
     * Show selections dashboard page
     *
     * @param $project_id
     * @return Response
     */
    public function dashboard($project_id): Response
    {
        return Inertia::render('Core::Selections/Dashboard', [
            'project_id' => $project_id,
            'selection_types' => Tenant::current()->selection_types->map(fn(SelectionType $type) => [
                'name' => $type->name,
                'prefix' => $type->prefix,
                'img' => $type->imgForTenant(),
            ]),
        ]);
    }
}
