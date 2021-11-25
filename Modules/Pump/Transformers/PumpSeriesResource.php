<?php

namespace Modules\Pump\Transformers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
use Modules\Core\Support\TenantStorage;

class PumpSeriesResource extends JsonResource
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
            'id' => $this->id,
            'brand_id' => $this->brand_id,
            'name' => $this->name,
            'power_adjustment_id' => $this->power_adjustment_id,
            'category_id' => $this->category_id,
            'types' => $this->types()->pluck('pump_types.id')->all(),
            'applications' => $this->applications()->pluck('pump_applications.id')->all(),
            'image' => $this->image,
            'picture' => (new TenantStorage())->urlToImage($this->image),
        ];
    }
}
