<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ModuleResourceServiceInterface;
use Illuminate\Auth\Access\AuthorizationException;
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

class ProjectsController extends Controller implements ModuleResourceServiceInterface
{
    public function indexPath(): string
    {
        return 'Core::Projects/Index';
    }

    public function createPath(): string
    {
        return 'Core::Projects/Create';
    }

    public function editPath(): string
    {
        return 'Core::Projects/Edit';
    }

    public function showPath(): string
    {
        return 'Core::Projects/Show';
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function index(): Response
    {
        $this->authorize('project_access');
        return Inertia::render($this->indexPath(), [
            'projects' => auth()->user()->projects()
                ->withCount('selections')
                ->with(['selections' => function ($query) {
                    $query->select('id', 'project_id', 'selected_pump_name', 'flow', 'head');
                }])
                ->get(),
        ]);
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
        return Inertia::render($this->createPath());
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
        $this->authorize('project_access_' . $project->id);
        return Inertia::render($this->showPath(), [
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
        $this->authorize('project_access_' . $project->id);
        return Inertia::render($this->editPath(), [
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
        $this->authorize('project_access_' . $project->id);
        $project->update($request->validated());
        return Redirect::route('projects.index');
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
        $this->authorize('project_access_' . $project->id);
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
        $this->authorize('project_access_' . $id);
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
        $this->authorize('project_access_' . $project->id);
        return $action->execute($project, $request);
    }
}
