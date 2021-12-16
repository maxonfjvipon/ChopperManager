<?php

namespace Modules\Selection\Services;

use Illuminate\Database\Eloquent\Collection;
use Inertia\Inertia;
use Inertia\Response;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\Selection\Contracts\SelectionsServiceContract;
use Modules\Selection\Entities\Selection;

abstract class SelectionsService implements SelectionsServiceContract
{
    protected PumpableSelectionService $service;

    public function __construct(PumpableSelectionService $service)
    {
        $this->service = $service;
    }

    /**
     * Dashboard view name
     *
     * @return string
     */
    public function indexPath(): string
    {
        return 'Selection::Dashboard';
    }

    /**
     * Show view name
     *
     * @return string
     */
    public function showPath(): string
    {
        return $this->createPath();
    }

    /**
     * Create view name
     *
     * @return string
     */
    public function createPath(): string
    {
        return $this->service->createPath();
    }

    /**
     * Show selection types dashboard view
     *
     * @param $project_id
     * @return Response
     */
    public function index($project_id): Response
    {
        return Inertia::render($this->indexPath(), [
            'project_id' => $project_id,
            'selection_types' => $this->selectionTypes()
                ->map(fn(SelectionType $type) => [
                    'name' => $type->name,
                    'pumpable_type' => $type->pumpable_type,
                    'img' => $type->imgForTenant()
                ])
        ]);
    }

    protected function selectionTypes(): Collection|array
    {
        return SelectionType::all();
    }

    public function show(Selection $selection): Response
    {
        return Inertia::render($this->showPath(), [
            'project_id' => $selection->project_id,
            'selection_props' => $this->service->selectionPropsResource(),
            'selection' => $this->service->selectionResource($selection)
        ]);
    }

    /**
     * Create selection view
     *
     * @param $project_id
     * @return Response
     */
    public function create($project_id): Response
    {
        return Inertia::render($this->createPath(), [
            'project_id' => $project_id,
            'selection_props' => $this->service->selectionPropsResource()
        ]);
    }
}
