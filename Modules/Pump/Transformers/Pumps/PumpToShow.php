<?php

namespace Modules\Pump\Transformers\Pumps;

use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
use Modules\Pump\Entities\Pump;

/**
 * Pump to show.
 */
final class PumpToShow extends JsonResource
{
    /**
     * @throws Exception
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return (match ($request->pumpable_type) {
            Pump::$DOUBLE_PUMP => new DoublePumpResource($this->resource),
            default => new SinglePumpResource($this->resource)
        })->toArray($request);
    }
}
