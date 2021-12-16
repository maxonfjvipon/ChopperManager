<?php


namespace Modules\PumpManager\Services\Selection;

use Illuminate\Support\Facades\Auth;
use Modules\Core\Support\TenantStorage;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\DoublePumpWorkScheme;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\MainsConnection;
use Modules\Pump\Entities\PumpType;
use Modules\Selection\Entities\LimitCondition;
use Modules\Selection\Entities\SelectionRange;

class PMDoublePumpSelectionService extends PMPumpableSelectionService
{
    public function selectionPropsResource(): array
    {
        $availableSeriesIds = Auth::user()->available_series()->pluck('id')->all();
        $availableBrands = Auth::user()->available_brands()->get();

        return [
            'brands' => $availableBrands->all(),
            'brandsWithSeries' => $availableBrands->load([
                'series' => function ($query) use ($availableSeriesIds) {
                    $query->whereIn('id', $availableSeriesIds);
                }, 'series.types', 'series.applications', 'series.power_adjustment'
            ])->all(),
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
