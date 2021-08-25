<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Matrix\Builder;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Projects/Index', [
            'projects' => auth()->user()->projects()->withCount(['selections' => function ($query) {
                $query->where('deleted', false);
            }])->where('deleted', false)->get()->all()
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
        Auth::user()->projects()->create($request->validated());
        return Redirect::route('projects.index')->with('success', 'Проект успешно создан');
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     * @return Response
     */
    public function show(Project $project): Response
    {
        return Inertia::render('Projects/Show', [
            'project' => new ProjectResource($project),
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
        $project->update($request->validated());
        return Redirect::back()->with('success', 'Проект успешно изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @return RedirectResponse
     */
    public function destroy(Project $project): RedirectResponse
    {
        $project->update(['deleted' => true]);
        return Redirect::back()->with('success', 'Проект успешно удален');
    }
}
