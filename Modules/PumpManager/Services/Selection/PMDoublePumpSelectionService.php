<?php


namespace Modules\PumpManager\Services\Selection;

use Illuminate\Support\Facades\Auth;
use Modules\Core\Support\TenantStorage;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\DoublePumpWorkScheme;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\MainsConnection;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpType;
use Modules\Selection\Entities\LimitCondition;
use Modules\Selection\Entities\SelectionRange;

class PMDoublePumpSelectionService extends PMPumpableSelectionService
{
    public function selectionPropsResource(): array
    {
        $availableSeriesIds = Auth::user()->available_series()
            ->double()
            ->pluck('id')
            ->all();

        return [
            'brandsWithSeries' => PumpBrand::with([
                'series' => function ($query) use ($availableSeriesIds) {
                    $query->whereIn('id', $availableSeriesIds)
                        ->notDiscontinued();
                }, 'series.types', 'series.applications', 'series.power_adjustment'])
                ->whereHas('series', function ($query) use ($availableSeriesIds) {
                    $query->whereIn('id', $availableSeriesIds);
                })->get(),
            'media_path' => (new TenantStorage())->urlToTenantFolder(), // TODO: fix this
            'connectionTypes' => ConnectionType::all(),
            'types' => PumpType::availableForUserSeries($availableSeriesIds)->get(),
            'mainsConnections' => MainsConnection::all(),
            'dns' => DN::all(),
            'powerAdjustments' => ElPowerAdjustment::all(),
            'limitConditions' => LimitCondition::all(),
            'selectionRanges' => SelectionRange::all(),
            'workSchemes' => DoublePumpWorkScheme::all(),
            'defaults' => [
                'brands' => [],
                'powerAdjustments' => [ElPowerAdjustment::firstWhere('id', 2)->id]
            ],
        ];
    }
}
