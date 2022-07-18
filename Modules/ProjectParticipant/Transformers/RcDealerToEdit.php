<?php

namespace Modules\ProjectParticipant\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Dealer to edit resource.
 */
final class RcDealerToEdit extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'phone' => $this->resource->phone,
            'area_id' => $this->resource->area_id,
            'itn' => $this->resource->itn,
            'email' => $this->resource->email,
            'without_pumps' => $this->resource->without_pumps,
            'available_series_ids' => $this->resource->available_series->pluck('id')->all(),
            'markups' => RcDealerMarkup::collection($this->resource->markups),
        ];
    }
}
