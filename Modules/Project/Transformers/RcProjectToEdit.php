<?php

namespace Modules\Project\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'installer_id' => $this->installer_id,
            'customer_id' => $this->customer_id,
            'designer_id' => $this->designer_id,
            'dealer_id' => $this->dealer_id
        ] : []);
    }
}
