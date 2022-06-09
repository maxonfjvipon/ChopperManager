<?php

namespace Modules\Selection\Transformers;

use App\Support\TenantStorage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\Pure;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\DoublePumpWorkScheme;
use Modules\PumpSeries\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\MainsConnection;
use Modules\PumpSeries\Entities\PumpApplication;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\PumpSeries\Entities\PumpType;
use Modules\Selection\Entities\LimitCondition;
use Modules\Selection\Entities\SelectionRange;

class SelectionPropsResource extends JsonResource implements Arrayable
{
    /**
     * Ctor.
     */
    #[Pure] public function __construct()
    {
        parent::__construct(null);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     * @throws Exception
     */
    public function toArray($request): array
    {
        $series_ids = Auth::user()->available_series()
            ->categorized($request)
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

    public function asArray(): array
    {
        return $this->toArray(request());
    }
}
