<?php

namespace Modules\Selection\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\Selection\Actions\MakeSelectionAction;
use Modules\Selection\Entities\SinglePumpSelection;
use Modules\Selection\Http\Requests\MakeSelectionRequest;
use Modules\Selection\Http\Requests\StoreSelectionRequest;
use Modules\Selection\Http\Requests\UpdateSelectionRequest;
use Modules\Selection\Transformers\SinglePumpSelectionResource;
use Modules\Selection\Transformers\SingleSelectionPropsResource;

class SelectionsController extends Controller
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
            'selection_types' => SelectionType::all()
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
            'selection_props' => new SingleSelectionPropsResource(null),
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
        return Inertia::render('Selections/SingleNew/Index', [
            'selection_props' => new SingleSelectionPropsResource(null),
            'project_id' => $selection->project_id,
            'selection' => new SinglePumpSelectionResource($selection)
        ]);
    }

    /**
     * Delete selection
     *
     * @param SinglePumpSelection $selection
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(SinglePumpSelection $selection): RedirectResponse
    {
        $this->authorize('selection_delete');
        $selection->delete();
        return Redirect::back();
    }

    /**
     * Create selection with data
     *
     * @param StoreSelectionRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(StoreSelectionRequest $request): RedirectResponse
    {
        $this->authorize('selection_create');
        SinglePumpSelection::create($request->validated());
        return Redirect::back()->with('success', __('flash.selections.added'));
    }

    /**
     * Update selection with data
     *
     * @param UpdateSelectionRequest $request
     * @param SinglePumpSelection $selection
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(UpdateSelectionRequest $request, SinglePumpSelection $selection): RedirectResponse
    {
        $this->authorize('selection_edit');
        $selection->update($request->validated());
        return Redirect::route('projects.show', $request->project_id);
    }

    /**
     * Select pumps
     *
     * @param MakeSelectionRequest $request
     * @param MakeSelectionAction $makeSelectionAction
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function select(MakeSelectionRequest $request, MakeSelectionAction $makeSelectionAction): JsonResponse
    {
        $this->authorize('selection_create');
        $this->authorize('selection_edit');
        return $makeSelectionAction->execute($request);
    }

    /*
     * Restore the specified resource
     */
    public function restore($id): RedirectResponse
    {
        $this->authorize('selection_restore');
        SinglePumpSelection::withTrashed()->find($id)->restore();
        return Redirect::back();
    }
}
