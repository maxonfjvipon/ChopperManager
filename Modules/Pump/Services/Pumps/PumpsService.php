<?php

namespace Modules\Pump\Services\Pumps;

use App\Traits\HasFilterData;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Pump\Contracts\Pumps\PumpsServiceContract;
use Modules\Pump\Services\Pumps\PumpType\PumpableTypePumpService;

abstract class PumpsService implements PumpsServiceContract
{
    use HasFilterData;

    protected PumpableTypePumpService $service;

    public function __construct(PumpableTypePumpService $service)
    {
        $this->service = $service;
    }

    /**
     * @return array
     */
    abstract protected function pumpsFilterDataResource(): array;

    abstract protected function loadedPumps(): array;

    public function index(): Response
    {
        return Inertia::render($this->indexPath(), [
            'filter_data' => $this->asFilterData($this->pumpsFilterDataResource()),
            'projects' => Auth::user()->projects()->get(['id', 'name'])
        ]);
    }

    public function indexPath(): string
    {
        return 'Pump::Pumps/Index';
    }

    public function showPath(): string
    {
        return 'Pump::Pumps/Show';
    }

    /**
     * Load pumps
     *
     * @return JsonResponse
     */
    public function load(): JsonResponse
    {
        return response()->json($this->loadedPumps());
    }
}
