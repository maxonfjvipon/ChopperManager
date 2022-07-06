<?php

namespace Modules\Project\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * Project to edit resource.
 */
final class RcProjectToEdit extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge([
            'id' => $this->id,
            'name' => $this->name,
            'area_id' => $this->area_id,
            'status' => $this->status->value,
            'description' => $this->description,
        ], Auth::user()->isAdmin() ? [
            'installer' => $this->installer?->full_name,
            'customer' => $this->customer?->full_name,
            'designer' => $this->designer?->full_name,
            'dealer_id' => $this->dealer_id
        ] : []);
    }
}
