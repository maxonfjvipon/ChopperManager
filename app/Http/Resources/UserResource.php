<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'inn' => $this->inn,
            'phone' => $this->phone,
            'area' => $this->city->area->name,
            'city' => $this->city->name,
            'fio' => $this->fio,
            'email' => $this->email,
            'business' => $this->business->name,
            'role' => $this->role->name,
        ];
    }
}
