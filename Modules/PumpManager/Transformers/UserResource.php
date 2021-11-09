<?php

namespace Modules\PumpManager\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'organization_name' => $this->organization_name,
            'itn' => $this->itn,
            'phone' => $this->phone,
            'country' => $this->country->name,
            'city' => $this->city,
            'postcode' => $this->postcode,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'business' => $this->business->name,
            'available_series_ids' => $this->available_series()->pluck('id')->all(),
            'available_selection_type_ids' => $this->available_selection_types()->pluck('id')->all()
        ];
    }
}
