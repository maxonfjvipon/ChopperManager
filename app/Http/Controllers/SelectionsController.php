<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeSelectionRequest;
use App\Http\Requests\StoreSelectionRequest;
use App\Http\Requests\UpdateSelectionRequest;
use App\Http\Resources\SinglePumpSelectionResource;
use App\Http\Resources\SingleSelectionPropsResource;
use App\Models\Selections\Single\SinglePumpSelection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Actions\MakeSelectionAction;

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
        return Inertia::render('Selections/Dashboard', ['project_id' => $project_id]);
    }

    /**
     * Show create selection form
     *
     * @param $project_id
     * @return Response
     */
    public function create($project_id): Response
    {
        return Inertia::render('Selections/Single', [
            'selection_props' => new SingleSelectionPropsResource(null),
            'project_id' => $project_id
        ]);
    }

    /**
     * Display selection
     *
     * @param SinglePumpSelection $selection
     * @return Response
     */
    public function show(SinglePumpSelection $selection): Response
    {
        return Inertia::render('Selections/Single', [
            'selection_props' => new SingleSelectionPropsResource($selection),
            'project_id' => $selection->project_id,
            'selection' => new SinglePumpSelectionResource($selection)
        ]);
    }

    /**
     * Delete selection
     *
     * @param SinglePumpSelection $selection
     * @return RedirectResponse
     */
    public function destroy(SinglePumpSelection $selection): RedirectResponse
    {
        $selection->update(['deleted' => true]);
        return Redirect::back()->with('success', 'Подбор успешно удален');
    }

    /**
     * Create selection with data
     *
     * @param StoreSelectionRequest $request
     * @return RedirectResponse
     */
    public function store(StoreSelectionRequest $request): RedirectResponse
    {
        SinglePumpSelection::create($request->validated());
        return Redirect::back()->with('success', "Подбор успешно добавлен к проекту");
    }

    /**
     * Update selection with data
     *
     * @param UpdateSelectionRequest $request
     * @param SinglePumpSelection $selection
     * @return RedirectResponse
     */
    public function update(UpdateSelectionRequest $request, SinglePumpSelection $selection): RedirectResponse
    {
        $selection->update($request->validated());
        return Redirect::route('projects.show', $request->project_id);
    }

    /**
     * Select pumps
     *
     * @param MakeSelectionRequest $request
     * @param MakeSelectionAction $makeSelectionAction
     * @return JsonResponse
     */
    public function select(MakeSelectionRequest $request, MakeSelectionAction $makeSelectionAction): JsonResponse
    {
        return $makeSelectionAction->execute($request->validated());
    }
}
