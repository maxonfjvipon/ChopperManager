<?php

namespace Modules\Project\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class RcSelectionOfProject extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'created_at' => formatted_date($this->resource->created_at),
            'updated_at' => formatted_date($this->resource->updated_at),
            'station_type' => $this->resource->station_type->description,
            'type' => $this->resource->type->description,
            'flow' => $this->resource->flow,
            'head' => $this->resource->head,
            'comment' => $this->resource->comment
        ];
    }
}
