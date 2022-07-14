<?php

namespace Modules\ProjectParticipant\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Project of participant resource.
 */
final class RcProjectOfParticipant extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     */
    public function toArray($request): array
    {
        return $this->resource->asArray();
    }
}
