<?php

namespace App\Http\Resources;

use App\Models\ConnectionType;
use App\Models\MainsConnection;
use App\Models\DN;
use App\Models\LimitCondition;
use App\Models\Pumps\PumpApplication;
use App\Models\Pumps\PumpBrand;
use App\Models\Pumps\ElPowerAdjustment;
use App\Models\Pumps\PumpType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleSelectionPropsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'brands' => PumpBrand::all(),
            'brandsWithSeries' => PumpBrand::with(['series' => function ($query) {
                $query->orderBy('name');
            }, 'series.types', 'series.applications', 'series.power_adjustment'])
                ->get(),
            'types' => PumpType::all(),
            'connectionTypes' => ConnectionType::all(),
            'applications' => PumpApplication::all(),
            'mainsConnections' => MainsConnection::all(),
            'dns' => DN::all(),
            'powerAdjustments' => ElPowerAdjustment::all(),
            'limitConditions' => LimitCondition::all(),
            'defaults' => [
                'brands' => PumpBrand::whereName('Wilo')->pluck('id')->all(), // todo: default
                'powerAdjustments' => [ElPowerAdjustment::firstWhere('id', 2)->id]
            ],
        ];
    }
}
