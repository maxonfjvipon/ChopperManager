<?php

namespace Modules\Selection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ModuleResourceController;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Response;
use Modules\Core\Entities\Project;
use Modules\Selection\Actions\ExportAtOnceSinglePumpSelectionAction;
use Modules\Selection\Actions\ExportSinglePumpSelectionAction;
use Modules\Selection\Actions\MakeSelectionCurvesAction;
use Modules\Selection\Actions\MakeSelectionAction;
use Modules\Selection\Entities\SinglePumpSelection;
use Modules\Selection\Http\Requests\ExportAtOnceSinglePumpSelectionRequest;
use Modules\Selection\Http\Requests\ExportSinglePumpSelectionRequest;
use Modules\Selection\Http\Requests\CurvesForSelectionRequest;
use Modules\Selection\Http\Requests\MakeSinglePumpSelectionRequest;
use Modules\Selection\Http\Requests\StoreSinglePumpSelectionRequest;
use Modules\Selection\Http\Requests\UpdateSinglePumpSelectionRequest;
use Modules\Selection\Services\SinglePumpSelectionService;
use Modules\Selection\Services\SinglePumpSelectionServiceInterface;

class SinglePumpSelectionsController extends Controller
{
    protected SinglePumpSelectionServiceInterface $service;

    public function __construct(SinglePumpSelectionServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Show selections dashboard page
     *
     * @param $project_id
     * @return Response
     */
    public function index($project_id): Response
    {
        return $this->service->__index($project_id);
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
        return $this->service->__create($project_id);
    }

    /**
     * Display selection
     *
     * @param SinglePumpSelection $selection
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function show(SinglePumpSelection $selection): RedirectResponse|Response
    {
        $this->authorize('selection_show');
        return $this->service->__show($selection);
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
     * @param StoreSinglePumpSelectionRequest $request
     * @param Project $project
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(StoreSinglePumpSelectionRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('selection_create');
        $project->selections()->create($request->validated());
//        SinglePumpSelection::create($request->validated());
        return Redirect::back()->with('success', __('flash.selections.added'));
    }

    /**
     * Update selection with data
     *
     * @param UpdateSinglePumpSelectionRequest $request
     * @param SinglePumpSelection $selection
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(UpdateSinglePumpSelectionRequest $request, SinglePumpSelection $selection): RedirectResponse
    {
        $this->authorize('selection_edit');
        $selection->update($request->validated());
        return Redirect::route('projects.show', $request->project_id);
    }

    /**
     * Select pumps
     *
     * @param MakeSinglePumpSelectionRequest $request
     * @param MakeSelectionAction $makeSelectionAction
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function select(MakeSinglePumpSelectionRequest $request, MakeSelectionAction $makeSelectionAction): JsonResponse
    {
        $this->authorize('selection_create');
        $this->authorize('selection_edit');
        return $makeSelectionAction->execute($request);
    }

    /**
     * Restore selection
     *
     * @param $id
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function restore($id): RedirectResponse
    {
        $this->authorize('selection_restore');
        SinglePumpSelection::withTrashed()->find($id)->restore();
        return Redirect::back();
    }

    /**
     * Get image for selection
     *
     * @param CurvesForSelectionRequest $request
     * @param MakeSelectionCurvesAction $action
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function curves(CurvesForSelectionRequest $request, MakeSelectionCurvesAction $action): JsonResponse
    {
        $this->authorize('selection_create');
        $this->authorize('selection_edit');
        return $action->execute($request);
    }

    /**
     * Export the selection
     *
     * @param ExportSinglePumpSelectionRequest $request
     * @param SinglePumpSelection $selection
     * @param ExportSinglePumpSelectionAction $action
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function export(ExportSinglePumpSelectionRequest $request, SinglePumpSelection $selection, ExportSinglePumpSelectionAction $action): \Illuminate\Http\Response
    {
        $this->authorize('selection_export');
        return $action->execute($request, $selection);
    }

    /**
     * Export selection in moment
     *
     * @param ExportAtOnceSinglePumpSelectionRequest $request
     * @param ExportAtOnceSinglePumpSelectionAction $action
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function exportAtOnce(ExportAtOnceSinglePumpSelectionRequest $request, ExportAtOnceSinglePumpSelectionAction $action): \Illuminate\Http\Response
    {
        $this->authorize('selection_export');
        return $action->execute($request);
    }
}
