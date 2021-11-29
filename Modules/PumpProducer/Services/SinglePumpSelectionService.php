<?php

namespace Modules\PumpProducer\Services;

use Inertia\Inertia;
use Inertia\Response;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\AdminPanel\Entities\Tenant;

class SinglePumpSelectionService extends \Modules\Selection\Services\SinglePumpSelectionService
{
    public function __index($project_id): Response
    {
        return Inertia::render($this->indexPath(), [
            'project_id' => $project_id,
            'selection_types' => Tenant::current()->selection_types->map(fn(SelectionType $type) => [
                'name' => $type->name,
                'prefix' => $type->prefix,
                'img' => $type->imgForTenant(),
            ]),
        ]);
    }
}
