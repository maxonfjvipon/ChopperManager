<?php

namespace Modules\Pump\Transformers;

use Exception;
use Illuminate\Http\Request;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Support\TxtDoublePumpMainCurvesView;

final class RcDoubleRcPump extends RcPump
{
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
            'id' => $this->id,
            'article_num_main' => $this->article_num_main,
            'article_num_archive' => $this->article_num_archive,
            'is_discontinued' => __($this->is_discontinued_with_series
                ? 'tooltips.popconfirm.no'
                : 'tooltips.popconfirm.yes'
            ),
            'full_name' => $this->full_name,
            'weight' => $this->weight,
            'price' => $this->price_list->price ?? null,
            'currency' => $this->price_list->currency->code_name ?? null,
            'rated_power' => $this->rated_power,
            'rated_current' => $this->rated_current,
            'connection_type' => $this->connection_type->name,
            'fluid_temp_min' => $this->fluid_temp_min,
            'fluid_temp_max' => $this->fluid_temp_max,
            'ptp_length' => $this->ptp_length,
            'dn_suction' => $this->dn_suction->value,
            'dn_pressure' => $this->dn_pressure->value,
            'category' => $this->series->category->name,
            'power_adjustment' => $this->series->power_adjustment->name,
            'connection' => $this->mains_connection->full_value,
            'applications' => $this->applications,
            'types' => $this->types,
            'description' => $this->description,
            'pumpable_type' => Pump::$DOUBLE_PUMP,
        ], $request->need_curves
            ? ['main_curves' => (new TxtDoublePumpMainCurvesView($this->resource))->asString()]
            : []);
        return $data;
    }
}
