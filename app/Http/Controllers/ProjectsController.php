<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\EditProjectResource;
use App\Http\Resources\ShowProjectResource;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        $this->authorize('project_access');
        return Inertia::render('Projects/Index', [
            'projects' => auth()->user()->projects()->withCount('selections')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProjectStoreRequest $request
     * @return RedirectResponse
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
     */
    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('project_delete');
        $project->delete();
        return Redirect::back();
    }

    /*
     * Restore the specified resource
     */
    public function restore($id): RedirectResponse
    {
        $this->authorize('project_restore');
        Project::withTrashed()->find($id)->restore();
        return Redirect::route('projects.index');
    }
}
