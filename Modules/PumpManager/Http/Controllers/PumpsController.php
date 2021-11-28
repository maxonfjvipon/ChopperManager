<?php

namespace Modules\PumpManager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\MainsConnection;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpApplication;
use Modules\Pump\Entities\PumpCategory;
use Modules\Pump\Entities\PumpType;
use Modules\Pump\Transformers\PumpResource;

class PumpsController extends \Modules\Pump\Http\Controllers\PumpsController
{
    protected function pumpFilterData(): array
    {
        return $this->asFilterData([
            'brands' => Auth::user()->available_brands()->pluck('name')->all(),
            'series' => Auth::user()->available_series()->pluck('name')->all(),
            'categories' => PumpCategory::pluck('name')->all(),
            'connections' => ConnectionType::pluck('name')->all(),
            'dns' => DN::pluck('value')->all(),
            'power_adjustments' => ElPowerAdjustment::pluck('name')->all(),
            'mains_connections' => MainsConnection::all()->map(fn($mc) => $mc->full_value)->toArray(),
            'types' => PumpType::pluck('name')->all(),
            'applications' => PumpApplication::pluck('name')->all(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function index(): Response
    {
        $this->authorize('pump_access');
        return Inertia::render($this->indexPath, [
            'pumps' => Inertia::lazy($this->lazyLoadedPumps(Auth::user()->available_pumps())),
            'filter_data' => $this->pumpFilterData()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Pump $pump
     * @return Response
     * @throws AuthorizationException
     */
    public function show(Pump $pump): Response
    {
        $this->authorize('pump_show');
        abort_if(!in_array($pump->id, Auth::user()->available_pumps()->pluck('id')->all()), 401);
        return Inertia::render($this->showPath, [
            'pump' => new PumpResource($pump),
        ]);
    }
}
