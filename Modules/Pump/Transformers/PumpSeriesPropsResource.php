<?php

namespace Modules\Pump\Transformers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\PumpApplication;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpCategory;
use Modules\Pump\Entities\PumpType;

class PumpSeriesPropsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'brands' => PumpBrand::all(),
            'categories' => PumpCategory::all(),
            'power_adjustments' => ElPowerAdjustment::all(),
            'applications' => PumpApplication::all(),
            'types' => PumpType::all()
        ];
    }
}
