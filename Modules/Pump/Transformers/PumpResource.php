<?php

namespace Modules\Pump\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Pump\Support\PumpCoefficientsHelper;
use Modules\Selection\Support\PPumpPerformance;
use Modules\Selection\Support\PumpPerformance;
use Modules\Selection\Support\Regression;
use Modules\Selection\Traits\HasAxisStep;

class PumpResource extends JsonResource
{
    use HasAxisStep;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $pumpPerformance = new PPumpPerformance($this->resource);
        $performanceLine = $pumpPerformance->asRegressedPointArray();
        $xMax = $performanceLine[count($performanceLine) - 1]['x'];
        $yMax = $pumpPerformance->hMax();

        return [
            'id' => $this->id,
            'article_num_main' => $this->article_num_main,
            'article_num_reserve' => $this->article_num_reserve,
            'article_num_archive' => $this->article_num_archive,
            'full_name' => $this->full_name,
            'weight' => $this->weight,
            'price' => $this->price_list->price ?? null,
            'currency' => $this->price_list->currency->name_code ?? null,
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
            'connection' => $this->connection->full_value,
            'applications' => $this->applications,
            'types' => $this->types,
            'description' => $this->description,
            'svg' => view('pump::pump_performance', [
                'performance_line' => $performanceLine,
                'dx' => 900 / $xMax,
                'dy' => 400 / $yMax,
                'x_axis_step' => $this->axisStep($xMax),
                'y_axis_step' => $this->axisStep($yMax),
            ])->render(),
        ];
    }
}
