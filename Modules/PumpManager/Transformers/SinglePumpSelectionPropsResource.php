<?php

namespace Modules\PumpManager\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
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
     * @param Request
     * @return array
     */
    public function toArray($request): array
    {
        $availableBrands = Auth::user()->available_brands();
        return [
            'brands' => $availableBrands->get()->all(),
            'brandsWithSeries' => $availableBrands->with([
                'series' => function ($query) {
                    $query->whereIn('id', Auth::user()->available_series()->pluck('id')->all());
                }, 'series.types', 'series.applications', 'series.power_adjustment'
            ])->get(),
            'types' => PumpType::all(),
            'media_path' => (new TenantStorage())->urlToTenantFolder(), // TODO: fix this
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
