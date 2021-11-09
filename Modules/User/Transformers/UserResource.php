<?php

namespace Modules\User\Transformers;

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
        ];
    }
}
