<?php

namespace Modules\Selection\Support;

use App\Support\TenantStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\DoublePumpWorkScheme;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\MainsConnection;
use Modules\Pump\Entities\PumpApplication;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Entities\PumpType;
use Modules\Selection\Entities\LimitCondition;
use Modules\Selection\Entities\SelectionRange;

/**
 * Selection props as array
 * @package Modules\Selection\Support
 */
final class ArrSelectionProps implements Arrayable
{
    /**
     * @var Request $request
     */
    private Request $request;

    /**
     * Ctor.
     * @param ?Request $request
     */
    public function __construct(Request $request = null)
    {
        $this->request = $request ?? request();
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        $series_ids = Auth::user()->available_series()
            ->categorized($this->request)
            ->notDiscontinued()
            ->pluck('id')
            ->all();
        $seriesCallback = fn($field = 'series_id') => function ($query) use ($series_ids, $field) {
            $query->whereIn($field, $series_ids);
        };
        $mediaPath = (new TenantStorage())->urlToTenantFolder();
        return [
            'selection_props' => [
                'brands_with_series' => PumpBrand::with([
                    'series' => $seriesCallback('id'),
                    'series.types',
                    'series.applications',
                    'series.power_adjustment'
                ])->whereHas('series', $seriesCallback('id'))
                    ->get()
                    ->transform(function (PumpBrand $brand) use ($mediaPath) {
                        $brand->series
                            ->transform(function (PumpSeries $series) use ($mediaPath) {
                                $series->image = $mediaPath . $series->image;
                                return $series;
                            });
                        return $brand;
                    }),
                'types' => PumpType::whereHas('series', $seriesCallback())->get(),
                'applications' => PumpApplication::whereHas('series', $seriesCallback())->get(),
                'connection_types' => ConnectionType::allOrCached(),
                'mains_connections' => MainsConnection::allOrCached(),
                'dns' => DN::allOrCached(),
                'power_adjustments' => ElPowerAdjustment::allOrCached(),
                'limit_conditions' => LimitCondition::allOrCached(),
                'selection_ranges' => SelectionRange::allOrCached(),
                'work_schemes' => DoublePumpWorkScheme::allOrCached(),
                'defaults' => [
                    'brands' => [],
                    'power_adjustments' => [ElPowerAdjustment::allOrCached()
                        ->firstWhere('id', 2)->id]
                ]
            ]
        ];
    }
}
