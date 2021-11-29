<?php


namespace Modules\Selection\Services;

use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\Selection\Entities\SinglePumpSelection;
use Modules\Selection\Transformers\SinglePumpSelectionPropsResource;
use Modules\Selection\Transformers\SinglePumpSelectionResource;

class SinglePumpSelectionService implements SinglePumpSelectionServiceInterface
{
    /**
     * @param $project_id
     * @return Response
     */
    public function __index($project_id): Response
    {
        return Inertia::render($this->indexPath(), [
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
     * @param $project_id
     * @return Response
     */
    public function __create($project_id): Response
    {
        return Inertia::render($this->createPath(), [
            'selection_props' => new SinglePumpSelectionPropsResource(null),
            'project_id' => $project_id
        ]);
    }

    /**
     * @param SinglePumpSelection $selection
     * @return Response|RedirectResponse
     */
    public function __show(SinglePumpSelection $selection): Response|RedirectResponse
    {
        return Inertia::render($this->showPath(), [
            'selection_props' => new SinglePumpSelectionPropsResource(null),
            'project_id' => $selection->project_id,
            'selection' => new SinglePumpSelectionResource($selection)
        ]);
    }

    public function indexPath(): string
    {
        return 'Selection::Dashboard';
    }

    public function showPath(): string
    {
        return $this->createPath();
    }

    public function editPath(): string
    {
    }

    public function createPath(): string
    {
        return 'Selection::Index';
    }
}
