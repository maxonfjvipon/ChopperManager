<?php

namespace App\Http\Resources;

use App\Support\Selections\PumpPerformance;
use App\Support\Selections\Regression;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PumpResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $coefficients = $this->coefficients->firstWhere('position', 1);
        $performanceLineData = PumpPerformance::by($this->performance)
            ->asPerformanceLineData(1, Regression::withCoefficients([$coefficients->k, $coefficients->b, $coefficients->c]));
        return [
            'id' => $this->id,
            'article_num_main' => $this->article_num_main,
            'article_num_reserve' => $this->article_num_reserve,
            'article_num_archive' => $this->article_num_archive,
            'full_name' => $this->full_name,
            'weight' => $this->weight,
            'price' => $this->price,
            'currency' => $this->currency->name_code,
            'rated_power' => $this->rated_power,
            'rated_current' => $this->rated_current,
            'connection_type' => $this->connection_type->name,
            'fluid_temp_min' => $this->fluid_temp_min,
            'fluid_temp_max' => $this->fluid_temp_max,
            'ptp_length' => $this->ptp_length,
            'dn_suction' => $this->dn_suction->value,
            'dn_pressure' => $this->dn_pressure->value,
            'category' => $this->category->name,
            'power_adjustment' => $this->series->power_adjustment->name,
            'connection' => $this->connection->full_value,
            'applications' => $this->imploded_applications,
            'types' => $this->imploded_types,
            'performance' => [
                'line_data' => $performanceLineData['line'],
                'y_max' => $performanceLineData['yMax']
            ]
        ];
    }
}
