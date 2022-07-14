<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * User to edit resource.
 */
final class RcUserToEdit extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'dealer_id' => $this->resource->dealer_id,
            'itn' => $this->resource->itn,
            'phone' => $this->resource->phone,
            'first_name' => $this->resource->first_name,
            'middle_name' => $this->resource->middle_name,
            'last_name' => $this->resource->last_name,
            'email' => $this->resource->email,
            'area_id' => $this->resource->area_id,
            'is_active' => $this->resource->is_active,
            'role' => $this->resource->role->value,
        ];
    }
}
