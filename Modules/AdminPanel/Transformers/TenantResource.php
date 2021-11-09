<?php

namespace Modules\AdminPanel\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'database' => $this->database,
            'domain' => $this->domain,
            'is_active' => $this->is_active,
            'has_registration' => $this->has_registration,
            'type' => $this->type->id,
            'selection_type_ids' => $this->selection_types()->pluck('id')->all(),
        ];
    }
}
