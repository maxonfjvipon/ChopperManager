<?php

namespace Modules\ProjectParticipant\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

final class RcProjectOfDealer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
