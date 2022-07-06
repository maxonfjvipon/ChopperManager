<?php

namespace Modules\Project\Transformers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Project to show resource.
 */
final class RcProjectToShow extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     * @throws Exception
     */
    public function toArray($request): array
    {
        return array_merge(
            $this->resource->asArray(),
            ['selections' => RcSelectionOfProject::collection($this->resource->selections)]
        );
    }
}
