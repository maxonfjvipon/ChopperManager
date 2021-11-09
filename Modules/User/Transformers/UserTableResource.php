<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UserTableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'created_at' => $this->created_at,
            'organization_name' => $this->organization_name,
            'itn' => $this->itn,
            'phone' => $this->phone,
            'country' => $this->country->name,
            'city' => $this->city,
            'postcode' => $this->postcode,
            'currency' => $this->currency->code,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'main_business' => $this->business->name,
        ];
    }
}
