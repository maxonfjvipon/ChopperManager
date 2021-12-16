<?php

namespace Modules\Selection\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Response;
use Modules\Core\Entities\Project;
use Modules\Pump\Http\Requests\PumpableRequest;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\ExportAtOnceSelectionRequest;
use Modules\Selection\Http\Requests\ExportSelectionRequest;
use Modules\Selection\Http\Requests\CurvesForSelectionRequest;
use Modules\Selection\Http\Requests\MakeSelectionRequest;
use Modules\Selection\Http\Requests\SelectionRequest;
use Modules\Selection\Services\PumpType\PumpableTypeSelectionService;
use Modules\Selection\Services\SelectionsService;

class SelectionsController extends Controller
{
    private SelectionsService $service;
    private PumpableTypeSelectionService $manager;

    /**
     * SelectionsController constructor.
     * @param SelectionsService $service
     * @param PumpableTypeSelectionService $manager
     */
    public function __construct(SelectionsService $service, PumpableTypeSelectionService $manager)
    {
        $this->service = $service;
        $this->manager = $manager;
    }

    /**
     * Show selections dashboard page
     *
     * @param $project_id
     * @return Response
     */
    public function index($project_id): Response
    {
        return $this->service->index($project_id);
    }

    /**
     * Show create selection form
     *
     * @param PumpableRequest $request
     * @param $project_id
     * @return Response
     * @throws AuthorizationException
     */
    public function create(PumpableRequest $request, $project_id): Response
    {
        $this->authorize('selection_create');
        if ($project_id !== "-1")
            $this->authorize('project_access_' . $project_id);
        return $this->service->create($project_id);
    }

    /**
     * Display selection
     *
     * @param Selection $selection
     * @return Response
     * @throws AuthorizationException
     */
    public function show(Selection $selection): Response
    {
        $this->authorize('selection_show');
        $this->authorize('project_access_' . $selection->project_id);
        return $this->service->show($selection);
    }

    /**
     * Delete selection
     *
     * @param Selection $selection
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Selection $selection): RedirectResponse
    {
        $this->authorize('selection_delete');
        $this->authorize('project_access_' . $selection->project_id);
        $selection->delete();
        return Redirect::back();
    }

    /**
     * Create selection with data
     *
     * @param SelectionRequest $request
     * @param Project $project
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(SelectionRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('selection_create');
        $this->authorize('project_access_' . $project->id);
        $project->selections()->create($request->validated());
        return Redirect::back()->with('success', __('flash.selections.added'));
    }

    /**
     * Update selection with data
     *
     * @param SelectionRequest $request
     * @param Selection $selection
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(SelectionRequest $request, Selection $selection): RedirectResponse
    {
        $this->authorize('selection_edit');
        $this->authorize('project_access_' . $selection->project_id);
        $selection->update($request->validated());
        return Redirect::route('projects.show', $request->project_id);
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
        $selection = Selection::withTrashed()->find($id);
        $this->authorize('project_access_' . $selection->project_id);
        $selection->restore();
        return Redirect::back();
    }

    /**
     * Select pumps
     *
     * @param MakeSelectionRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function select(MakeSelectionRequest $request): JsonResponse
    {
        $this->authorize('selection_create');
        $this->authorize('selection_edit');
        return $this->manager->select($request);
    }

    /**
     * Get image for selection
     *
     * @param CurvesForSelectionRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function curves(CurvesForSelectionRequest $request): JsonResponse
    {
        $this->authorize('selection_create');
        $this->authorize('selection_edit');
        return $this->manager->curves($request);
    }

    /**
     * Export the selection
     *
     * @param ExportSelectionRequest $request
     * @param Selection $selection
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function export(ExportSelectionRequest $request, Selection $selection): \Illuminate\Http\Response
    {
        $this->authorize('selection_export');
        $this->authorize('project_access_' . $selection->project_id);
        return $this->manager->export($request, $selection);
    }

    /**
     * Export selection in moment
     *
     * @param ExportAtOnceSelectionRequest $request
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function exportAtOnce(ExportAtOnceSelectionRequest $request): \Illuminate\Http\Response
    {
        $this->authorize('selection_export');
        return $this->manager->exportAtOnce($request);
    }
}
