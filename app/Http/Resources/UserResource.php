<?php

namespace App\Http\Resources;

use App\Models\Pumps\PumpBrand;
use App\Models\Pumps\PumpSeries;
use Illuminate\Database\Eloquent\Relations\MorphTo;
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
            'organization_name' => $this->organization_name,
            'itn' => $this->itn,
            'phone' => $this->phone,
            'country_id' => $this->country_id,
            'city' => $this->city,
            'postcode' => $this->postcode,
            'currency_id' => $this->currency_id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'business_id' => $this->business_id,
        ];
    }
}
