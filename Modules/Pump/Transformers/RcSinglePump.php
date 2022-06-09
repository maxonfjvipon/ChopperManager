<?php

namespace Modules\Pump\Transformers;

use Exception;
use Illuminate\Http\Request;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Http\Endpoints\EpShowPump;
use Modules\Selection\Support\TxtSinglePumpAddCurvesView;
use Modules\Selection\Support\TxtSinglePumpMainCurvesView;
use Modules\Selection\Traits\AxisStep;

/**
 * Single pump to show resource
 * @see EpShowPump
 */
final class RcSinglePump extends RcPump
{
    use AxisStep;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function toArray($request): array
    {
        $data = array_merge(parent::toArray($request), [
            'id' => $this->resource->id,
            'article_num_main' => $this->resource->article_num_main,
            'article_num_archive' => $this->resource->article_num_archive,
            'is_discontinued' => __($this->resource->is_discontinued_with_series
                ? 'tooltips.popconfirm.no'
                : 'tooltips.popconfirm.yes'
            ),
            'full_name' => $this->resource->full_name,
            'weight' => $this->resource->weight,
            'price' => $this->resource->price_list->price ?? null,
            'currency' => $this->resource->price_list->currency->code_name ?? null,
            'rated_power' => $this->resource->rated_power,
            'rated_current' => $this->resource->rated_current,
            'connection_type' => $this->resource->connection_type->name,
            'fluid_temp_min' => $this->resource->fluid_temp_min,
            'fluid_temp_max' => $this->resource->fluid_temp_max,
            'ptp_length' => $this->resource->ptp_length,
            'dn_suction' => $this->resource->dn_suction->value,
            'dn_pressure' => $this->resource->dn_pressure->value,
            'category' => $this->resource->series->category->name,
            'power_adjustment' => $this->resource->series->power_adjustment->name,
            'connection' => $this->resource->mains_connection->full_value,
            'applications' => $this->resource->applications,
            'types' => $this->resource->types,
            'description' => $this->resource->description,
            'pumpable_type' => Pump::$SINGLE_PUMP,
        ]);
        if ($request->need_curves) {
            $data = array_merge($data, [
                'main_curves' => (new TxtSinglePumpMainCurvesView($this->resource))->asString(),
            ], $this->resource->HE_performance != null ? [ // todo: all additional performances
                'additional_curves' => (new TxtSinglePumpAddCurvesView($this->resource))->asString()
            ] : []);
        }
        return $data;
    }
}
