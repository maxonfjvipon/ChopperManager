<?php


namespace Modules\PumpManager\Services;

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

class PumpsService extends \Modules\Pump\Services\Pumps\PumpsService
{
    /**
     * @return array
     */
    protected function pumpFilterData(): array
    {
        $availableSeries = Auth::user()->available_series()->get();
        return $this->asFilterData([
            'brands' => Auth::user()->available_brands($availableSeries->pluck('id')->all())->pluck('name')->all(),
            'series' => $availableSeries->pluck('name')->all(),
            'categories' => PumpCategory::pluck('name')->all(),
            'connections' => ConnectionType::pluck('name')->all(),
            'dns' => DN::pluck('value')->all(),
            'power_adjustments' => ElPowerAdjustment::pluck('name')->all(),
            'mains_connections' => MainsConnection::all()->map(fn($mc) => $mc->full_value)->toArray(),
            'types' => PumpType::availableForUserSeries($availableSeries->pluck('id')->all())->pluck('name')->all(),
            'applications' => PumpApplication::availableForUserSeries($availableSeries->pluck('id')->all())->pluck('name')->all(),
        ]);
    }

    /**
     * @return Response
     */
    public function __index(): Response
    {
        return Inertia::render($this->indexPath(), [
            'pumps' => Inertia::lazy($this->lazyLoadedPumps(Auth::user()->available_pumps())),
            'filter_data' => $this->pumpFilterData()
        ]);
    }

    /**
     * @param Pump $pump
     * @return Response
     */
    public function __show(Pump $pump): Response
    {
        abort_if(!in_array($pump->id, Auth::user()->available_pumps()->pluck('id')->all()), 401);
        return Inertia::render($this->showPath(), [
            'pump' => new PumpResource($pump),
        ]);
    }
}
