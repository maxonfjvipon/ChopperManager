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
            'id' => $this->id,
            'part_num_main' => $this->part_num_main,
            'part_num_backup' => $this->part_num_backup,
            'part_num_archive' => $this->part_num_archive,
            'producer' => $this->producer->name,
            'series' => $this->series->name,
            'name' => $this->name,
            'price' => $this->price,
            'currency' => $this->currency->name,
            'weight' => $this->weight,
            'power' => $this->power,
            'amperage' => $this->amperage,
            'connection_type' => $this->connection_type->name,
            'min_liquid_temp' => $this->min_liquid_temp,
            'max_liquid_temp' => $this->max_liquid_temp,
            'between_axes_dist' => $this->between_axes_dist,
            'dn_input' => $this->dn_input->value,
            'dn_output' => $this->dn_output->value,
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
