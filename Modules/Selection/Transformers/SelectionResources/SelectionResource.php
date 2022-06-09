<?php

namespace Modules\Selection\Transformers\SelectionResources;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrExploded;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\Pump\Entities\Pump;

class SelectionResource extends JsonResource implements Arrayable
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
        return ['selection' => [
            'data' => (match ($this->resource->pump_type) {
                Pump::$DOUBLE_PUMP => (new DoublePumpSelectionResource($this->resource)),
                Pump::$SINGLE_PUMP => (new SinglePumpSelectionResource($this->resource))
            })->toArray($request)
        ]];
    }

    /**
     * @throws Exception
     */
    protected function arrayOfIntsFromString($string): array
    {
        return $string === null
            ? []
            : (new ArrMapped(
                ArrExploded::byComma($string),
                'intval'
            ))->asArray();
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return $this->toArray(request());
    }
}
