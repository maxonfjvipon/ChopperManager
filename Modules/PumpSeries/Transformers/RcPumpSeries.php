<?php

namespace Modules\PumpSeries\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class RcPumpSeries extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'brand_id' => $this->brand_id,
            'name' => $this->name,
            'currency' => $this->currency->value,
            'is_discontinued' => $this->is_discontinued,
        ];
    }
}
