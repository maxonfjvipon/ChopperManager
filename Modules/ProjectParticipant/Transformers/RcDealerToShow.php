<?php

namespace Modules\ProjectParticipant\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Project\Entities\Project;

/**
 * Dealer to show resource.
 */
final class RcDealerToShow extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request): array
    {
//        dd($this->resource->projects);
        return array_merge(
            $this->resource->asArray(),
            ['projects' => $this->resource->projects->map(fn(Project $project) => $project->asArray())]
        );
    }
}
