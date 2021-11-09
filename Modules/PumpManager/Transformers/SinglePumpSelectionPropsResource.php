<?php

namespace Modules\PumpManager\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\MainsConnection;
use Modules\Pump\Entities\PumpApplication;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpType;
use Modules\Selection\Entities\LimitCondition;
use Modules\Selection\Entities\SelectionRange;

class SinglePumpSelectionPropsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request): array
    {
        $availableSeriesIds = Auth::user()->available_series()->pluck('id')->all();
        $availableBrands = PumpBrand::whereHas('series', function ($query) use ($availableSeriesIds) {
            $query->whereIn('id', $availableSeriesIds);
        });
        return [
            'brands' => $availableBrands->get()->all(),
            'brandsWithSeries' => $availableBrands->with(['series' => function ($query) use ($availableSeriesIds) {
                $query->whereIn('id', $availableSeriesIds)->orderBy('name');
            }, 'series.types', 'series.applications', 'series.power_adjustment'])->get(),
            'types' => PumpType::all(),
            'connectionTypes' => ConnectionType::all(),
            'applications' => PumpApplication::all(),
            'mainsConnections' => MainsConnection::all(),
            'dns' => DN::all(),
            'powerAdjustments' => ElPowerAdjustment::all(),
            'limitConditions' => LimitCondition::all(),
            'selectionRanges' => SelectionRange::all(),
            'defaults' => [
                'brands' => [],
//                'brands' => PumpBrand::whereName('Wilo')->pluck('id')->all(), // todo: default
                'powerAdjustments' => [ElPowerAdjustment::firstWhere('id', 2)->id]
            ],
        ];
    }
}
