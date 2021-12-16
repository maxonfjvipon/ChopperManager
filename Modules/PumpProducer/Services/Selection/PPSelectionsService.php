<?php

namespace Modules\PumpProducer\Services\Selection;

use Illuminate\Database\Eloquent\Collection;
use Inertia\Response;
use JetBrains\PhpStorm\Pure;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Selection\Services\SelectionsService;

class PPSelectionsService extends SelectionsService
{
    /**
     * PPSelectionsServices constructor.
     * @param PPPumpableSelectionService $service
     */
    #[Pure] public function __construct(PPPumpableSelectionService $service)
    {
        parent::__construct($service);
    }

    /**
     * @return Collection|array
     */
    protected function selectionTypes(): Collection|array
    {
        return Tenant::current()->selection_types;
    }
}
