<?php

namespace Modules\PumpManager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\PumpManager\Transformers\SinglePumpSelectionPropsResource;
use Modules\Selection\Entities\SinglePumpSelection;
use Modules\Selection\Transformers\SinglePumpSelectionResource;

class SelectionsController extends \Modules\Selection\Http\Controllers\SelectionsController
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
            'selection_types' => Auth::user()->available_selection_types
                ->map(fn(SelectionType $type) => [
                    'name' => $type->name,
                    'prefix' => $type->prefix,
                    'img' => $type->imgForTenant(),
                ]),
        ]);
    }

    /**
     * Show create selection form
     *
     * @param $project_id
     * @return Response
     * @throws AuthorizationException
     */
    public function create($project_id): Response
    {
        $this->authorize('selection_create');
        return Inertia::render('Selections/SingleNew/Index', [
            'selection_props' => new SinglePumpSelectionPropsResource(null),
            'project_id' => $project_id
        ]);
    }

    /**
     * Display selection
     *
     * @param SinglePumpSelection $selection
     * @return Response
     * @throws AuthorizationException
     */
    public function show(SinglePumpSelection $selection): Response
    {
        $this->authorize('selection_show');
        return Inertia::render('Selections/SingleNew/Index', [
            'selection_props' => new SinglePumpSelectionPropsResource(null),
            'project_id' => $selection->project_id,
            'selection' => new SinglePumpSelectionResource($selection)
        ]);
    }
}
