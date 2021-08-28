<?php

namespace App\Http\Resources;

use App\Models\Pumps\PumpProducer;
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
            'inn' => $this->inn,
            'phone' => $this->phone,
            'area_id' => $this->city->area->id,
            'city_id' => $this->city->id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'business_id' => $this->business->id,
        ];
    }
}
