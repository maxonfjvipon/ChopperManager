<?php

namespace Modules\ProjectParticipant\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class RcDealerMarkup extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'key' => $this->resource->id,
            'cost_from' => $this->resource->cost_from,
            'cost_to' => $this->resource->cost_to,
            'value' => $this->resource->value,
        ];
    }
}
