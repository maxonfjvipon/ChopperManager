<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * User to show resource.
 */
final class RcUserToShow extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     */
    public function toArray($request): array
    {
        return [
            'organization_name' => $this->resource->organization_name,
            'itn' => $this->resource->itn,
            'phone' => $this->resource->phone,
            'first_name' => $this->resource->first_name,
            'middle_name' => $this->resource->middle_name,
            'last_name' => $this->resource->last_name,
            'email' => $this->resource->email,
            'area' => $this->resource->area->name,
            'role' => $this->resource->role->value,
        ];
    }
}
