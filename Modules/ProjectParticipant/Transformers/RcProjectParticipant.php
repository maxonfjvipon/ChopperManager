<?php

namespace Modules\ProjectParticipant\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Project participant resource.
 */
class RcProjectParticipant extends JsonResource
{
    /**
     * @param  Request  $request
     */
    public function toArray($request): array
    {
        return array_merge(
            $this->resource->asArray(),
            ['projects' => RcProjectOfParticipant::collection($this->resource->projects)]
        );
    }
}
