<?php

namespace Modules\PumpManager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\PumpManager\Transformers\SinglePumpSelectionPropsResource;
use Modules\Selection\Entities\SinglePumpSelection;
use Modules\Selection\Transformers\SinglePumpSelectionResource;

class SinglePumpSelectionsController extends \Modules\Selection\Http\Controllers\SinglePumpSelectionsController
{
    /**
     * Show selections dashboard page
     *
     * @param $project_id
     * @return Response
     */
    public function index($project_id): Response
    {
        return Inertia::render($this->indexPath, [
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
        return Inertia::render($this->createPath, [
            'selection_props' => new SinglePumpSelectionPropsResource(null),
            'project_id' => $project_id
        ]);
    }

    /**
     * Display selection
     *
     * @param SinglePumpSelection $selection
     * @throws AuthorizationException
     */
    public function show(SinglePumpSelection $selection)
    {
        $this->authorize('selection_show');
        if (!in_array($selection->pump_id, Auth::user()->available_pumps()->pluck('id')->all())) {
            return Redirect::back()->with('warning', 'К сожалению данная серия больше вам не доступна. Пожалуйста свяижитесь с администратором');
        }
        return Inertia::render($this->showPath, [
            'selection_props' => new SinglePumpSelectionPropsResource(null),
            'project_id' => $selection->project_id,
            'selection' => new SinglePumpSelectionResource($selection)
        ]);
    }
}
