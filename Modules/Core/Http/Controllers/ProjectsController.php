<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Core\Actions\ExportProjectAction;
use Modules\Core\Entities\Project;
use Modules\Core\Http\Requests\ExportProjectRequest;
use Modules\Core\Http\Requests\ProjectStoreRequest;
use Modules\Core\Http\Requests\ProjectUpdateRequest;
use Modules\Core\Transformers\EditProjectResource;
use Modules\Core\Transformers\ShowProjectResource;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function index(): Response
    {
        $this->authorize('project_access');
        return Inertia::render('Projects/Index', [
            'projects' => auth()->user()->projects()
                ->withCount('selections')
                ->with(['selections' => function ($query) {
                    $query->select('id', 'project_id', 'selected_pump_name', 'flow', 'head');
                }])
                ->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProjectStoreRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(ProjectStoreRequest $request): RedirectResponse
    {
        $this->authorize('project_create');
        Auth::user()->projects()->create($request->validated());
        return Redirect::route('projects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     * @return Response
     * @throws AuthorizationException
     */
    public function show(Project $project): Response
    {
        $this->authorize('project_show');
        $this->authorize('selection_access');
        return Inertia::render('Projects/Show', [
            'project' => new ShowProjectResource($project),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Project $project
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(Project $project): Response
    {
        $this->authorize('project_edit');
        return Inertia::render('Projects/Edit', [
            'project' => new EditProjectResource($project)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Project $project
     * @param ProjectUpdateRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(ProjectUpdateRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('project_edit');
        $project->update($request->validated());
        return Redirect::route('projects.index');
    }

    /**
     * Display the create resource form.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create(): Response
    {
        $this->authorize('project_create');
        return Inertia::render('Projects/Create');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('project_delete');
        $project->delete();
        return Redirect::back();
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function restore($id): RedirectResponse
    {
        $this->authorize('project_restore');
        Project::withTrashed()->find($id)->restore();
        return Redirect::route('projects.index');
    }

    /**
     * Export project
     *
     * @param ExportProjectRequest $request
     * @param Project $project
     * @param ExportProjectAction $action
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function export(ExportProjectRequest $request, Project $project, ExportProjectAction $action): \Illuminate\Http\Response
    {
        $this->authorize('project_export');
        return $action->execute($project, $request);
    }
}
