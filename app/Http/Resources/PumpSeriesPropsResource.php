<?php

namespace App\Http\Resources;

use App\Models\Pumps\ElPowerAdjustment;
use App\Models\Pumps\PumpApplication;
use App\Models\Pumps\PumpBrand;
use App\Models\Pumps\PumpCategory;
use App\Models\Pumps\PumpType;
use Illuminate\Http\Resources\Json\JsonResource;

class PumpSeriesPropsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
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
