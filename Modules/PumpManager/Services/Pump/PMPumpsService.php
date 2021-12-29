<?php


namespace Modules\PumpManager\Services\Pump;

use Illuminate\Http\JsonResponse;
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
use Modules\Pump\Http\Requests\PumpShowRequest;
use Modules\Pump\Services\Pumps\PumpsService;

class PMPumpsService extends PumpsService
{
    /**
     * @return array
     */
    protected function pumpsFilterDataResource(): array
    {
        $availableSeries = Auth::user()->available_series;
        $availableSeriesIds = $availableSeries->pluck('id')->all();
        return [
            'brands' => Auth::user()
                ->available_brands()
                ->distinct()
                ->pluck('pump_brands.name')
                ->all(),
            'series' => $availableSeries->pluck('name')->all(),
//            'categories' => PumpCategory::pluck('name')->all(),
            'connections' => ConnectionType::pluck('name')->all(),
            'dns' => DN::pluck('value')->all(),
            'power_adjustments' => ElPowerAdjustment::pluck('name')->all(),
            'mains_connections' => MainsConnection::all()->map(fn($mc) => $mc->full_value)->toArray(),
            'types' => PumpType::availableForUserSeries($availableSeriesIds)->pluck('name')->all(),
            'applications' => PumpApplication::availableForUserSeries($availableSeriesIds)->pluck('name')->all(),
        ];
    }

    /**
     * @param PumpShowRequest $request
     * @param Pump $pump
     * @return JsonResponse
     */
    public function show(PumpShowRequest $request, Pump $pump): JsonResponse
    {
        abort_if(
            !in_array(
                $pump->id,
                Auth::user()->available_pumps()
                    ->onPumpableType($pump->pumpable_type)
                    ->pluck($pump->getTable() . '.id')
                    ->all()
            ),
            404
        );
        return response()->json($this->service->pumpResource($pump));
    }

    protected function loadedPumps($filter): array
    {
        return $this->service->pumpsBuilder($filter)
            ->availableForCurrentUser()
            ->get()
            ->map(fn(Pump $pump) => $this->service->loadPumpResource($pump))
            ->all();
    }
}
