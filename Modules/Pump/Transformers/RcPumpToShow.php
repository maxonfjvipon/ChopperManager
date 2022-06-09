<?php

namespace Modules\Pump\Transformers;

use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Http\Requests\RqShowPump;
use Modules\Selection\Support\TxtSinglePumpMainCurvesView;

/**
 * Pump to show.
 */
final class RcPumpToShow extends JsonResource
{
    /**
     * @throws Exception
     */
    public function toArray($request): array
    {
        return !$request->need_curves
            ? [
                'id' => $this->id,
                'article' => $this->article,
                'is_discontinued' => $this->is_discontinued_with_series,
                'brand' => $this->series->brand->name,
                'series' => $this->series->name,
                'name' => $this->name,
                'price' => $this->price,
                'currency' => $this->currency->key,
                'price_updated_at' => date_format($this->price_updated_at, 'd.m.Y'),
                'size' => $this->size,
                'weight' => $this->weight,
                'power' => $this->power,
                'current' => $this->current,
                'connection_type' => $this->connection_type->description,
                'orientation' => $this->orientation->description,
                'dn_suction' => $this->dn_suction,
                'dn_pressure' => $this->dn_pressure,
                'collector_switch' => $this->collector_switch->description,
                'suction_height' => $this->suction_height,
                'ptp_length' => $this->ptp_length,
            ] : [
                'curves' => (new TxtSinglePumpMainCurvesView($this->resource))->asString()
            ];
    }
}
