<?php

namespace Modules\Selection\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RcPumpStation extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'created_at' => $this->created_at,
            'id' => $this->id,
            'name' =>
//            'pump_id' => $this->pump_id,
            'cost_price' => $this->cost_price,
            'extra_percentage' => $this->extra_percentage,
            'extra_sum' => $this->extra_sum,
            'final_price' => $this->final_price,
            'comment' => $this->comment,
            'main_pumps_count' => $this->main_pumps_count,
            'reserve_pumps_count' => $this->reserve_pumps_count,
            'control_system_id' => $this->control_system_id
        ];
    }
}
