<?php

namespace Modules\Selection\Transformers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Selection\Support\TxtPumpStationName;

/**
 * Pump station resource.
 */
final class RcPumpStation extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     *
     * @throws Exception
     */
    public function toArray($request): array
    {
        // todo: key for front-end
        return array_merge(
            [
                'created_at' => formatted_date($this->resource->created_at),
                'updated_at' => formatted_date($this->resource->updated_at),
                'id' => $this->resource->id,
                'key' => $this->resource->id,
                'name' => (new TxtPumpStationName(
                    $this->resource->control_system,
                    $this->resource->main_pumps_count + $this->resource->reserve_pumps_count,
                    $this->resource->pump,
                    $this->resource->input_collector,
                    $this->resource->jockey_pump
                ))->asString(),
                'cost_price' => $this->resource->cost_price,
                'extra_percentage' => $this->resource->extra_percentage,
                'extra_sum' => $this->resource->extra_sum,
                'final_price' => $this->resource->final_price,
                'comment' => $this->resource->comment,
                'main_pumps_count' => $this->resource->main_pumps_count,
                'reserve_pumps_count' => $this->resource->reserve_pumps_count,
                'flow' => $this->resource->selection->flow,
                'head' => $this->resource->selection->head,

                'pump_id' => $this->resource->pump_id,
                'control_system_id' => $this->resource->control_system_id,
                'chassis_id' => $this->resource->chassis_id,
                'input_collector_id' => $this->resource->input_collector_id,
                'output_collector_id' => $this->resource->output_collector_id,
            ],
            $this->resource->control_system->has_jockey ? [
                'jockey_chassis_id' => $this->resource->jockey_chassis_id,
                'jockey_pump_id' => $this->resource->jockey_pump_id,
                'jockey_flow' => $this->resource->jockey_flow,
                'jockey_head' => $this->resource->jockey_head,
            ] : []
        );
    }
}
