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
        $coefficients = $this->coefficients->firstWhere('count', 1);
        $performanceLineData = (new PumpPerformance($this->performance))
            ->asPerformanceLineData(1, Regression::withCoefficients([$coefficients->k, $coefficients->b, $coefficients->c]));
        return [
            'article_num_main' => $this->article_num_main,
            'article_num_reserve' => $this->article_num_reserve,
            'article_num_archive' => $this->article_num_archive,
            'producer' => $this->producer->name,
            'series' => $this->series->name,
            'name' => $this->name,
            'weight' => $this->weight,
            'rated_power' => $this->rated_power,
            'rated_current' => $this->rated_current,
            'connection_type' => $this->connection_type->name,
            'min_fluid_temp' => $this->min_fluid_temp,
            'max_fluid_temp' => $this->max_fluid_temp,
            'center_distance' => $this->center_distance,
            'dn_suction' => $this->dn_suction->value,
            'dn_pressure' => $this->dn_pressure->value,
            'category' => $this->category->name,
            'regulation' => $this->regulation->name,
            'applications' => $this->applications,
            'types' => $this->types,
            'performance' => [
                'line_data' => $performanceLineData['line'],
                'y_max' => $performanceLineData['yMax']
            ]
        ];
    }
}
