<?php

namespace Modules\Components\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class RcArmature extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'article' => $this->article,
            'dn' => $this->dn,
            'length' => $this->length,
            'weight' => $this->weight,
            'price' => $this->price,
            'currency' => $this->currency->description,
            'price_updated_at' => formatted_date($this->price_updated_at),
        ];
    }
}
