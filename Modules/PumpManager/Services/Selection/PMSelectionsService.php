<?php


namespace Modules\PumpManager\Services\Selection;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use JetBrains\PhpStorm\Pure;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Services\SelectionsService;

class PMSelectionsService extends SelectionsService
{
    /**
     * PMSelectionsService constructor.
     * @param PMPumpableSelectionService $service
     */
    #[Pure] public function __construct(PMPumpableSelectionService $service)
    {
        parent::__construct($service);
    }

    /**
     * @return Collection|array
     */
    protected function selectionTypes(): Collection|array
    {
        return Auth::user()->available_selection_types;
    }

    public function show(Selection $selection): Response
    {
        $pump = $selection->pump;
        abort_if(
            !in_array($pump->id, Auth::user()->available_pumps()->pluck($pump->getTable() . '.id')->all()),
            401,
            "The series isn't available any more"
        );
        return parent::show($selection);
    }
}
