<?php

namespace Modules\Selection\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Support\TenantStorage;
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
            'media_path' => (new TenantStorage())->urlToTenantFolder(), // TODO: fix this
            'types' => PumpType::all(),
            'connectionTypes' => ConnectionType::all(),
            'applications' => PumpApplication::all(),
            'mainsConnections' => MainsConnection::all(),
            'dns' => DN::all(),
            'powerAdjustments' => ElPowerAdjustment::all(),
            'limitConditions' => LimitCondition::all(),
            'selectionRanges' => SelectionRange::all(),
            'defaults' => [
                'brands' => PumpBrand::whereName('Wilo')->pluck('id')->all(), // todo: default
                'powerAdjustments' => [ElPowerAdjustment::firstWhere('id', 2)->id]
            ],
        ];
    }
}
