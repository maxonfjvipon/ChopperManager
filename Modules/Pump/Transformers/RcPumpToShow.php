<?php

namespace Modules\Pump\Transformers;

use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Selection\Support\TxtSinglePumpMainCurvesView;

/**
 * Pump to show resource.
 */
final class RcPumpToShow extends JsonResource
{
    /**
     * @throws Exception
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return ArrMerged::new(
            new ArrIf(
                !!$request->need_info,
                fn() => [
                    'id' => $this->resource->id,
                    'article' => $this->resource->article,
                    'is_discontinued' => $this->resource->is_discontinued_with_series,
                    'brand' => $this->resource->series->brand->name,
                    'series' => $this->resource->series->name,
                    'name' => $this->resource->name,
                    'price' => $this->resource->price,
                    'currency' => $this->resource->currency->key,
                    'price_updated_at' => formatted_date($this->resource->price_updated_at),
                    'size' => $this->resource->size,
                    'weight' => $this->resource->weight,
                    'power' => $this->resource->power,
                    'current' => $this->resource->current,
                    'connection_type' => $this->resource->connection_type->description,
                    'orientation' => $this->resource->orientation->description,
                    'dn_suction' => $this->resource->dn_suction,
                    'dn_pressure' => $this->resource->dn_pressure,
                    'collector_switch' => $this->resource->collector_switch->description,
                    'suction_height' => $this->resource->suction_height,
                    'ptp_length' => $this->resource->ptp_length,
                ],
            ),
            new ArrIf(
                !!$request->need_curves,
                fn() => new ArrObject(
                    'curves',
                    new TxtSinglePumpMainCurvesView($this->resource)
                )
            )
        )->asArray();
    }
}
