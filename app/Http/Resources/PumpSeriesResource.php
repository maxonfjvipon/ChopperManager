<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PumpSeriesResource extends JsonResource
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
            'id' => $this->id,
            'brand_id' => $this->brand_id,
            'name' => $this->name,
            'power_adjustment_id' => $this->power_adjustment_id,
            'category_id' => $this->category_id,
            'types' => $this->types()->pluck('pump_types.id')->all(),
            'applications' => $this->applications()->pluck('pump_applications.id')->all(),
        ];
    }
}
