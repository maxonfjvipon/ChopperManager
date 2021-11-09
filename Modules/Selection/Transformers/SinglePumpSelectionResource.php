<?php

namespace Modules\Selection\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Support\StorageMediaHelper;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Support\PumpCoefficientsHelper;
use Modules\Selection\Support\IntersectionPoint;
use Modules\Selection\Support\PumpPerformance;
use Modules\Selection\Support\Regression;
use Modules\Selection\Support\SystemPerformance;

class SinglePumpSelectionResource extends JsonResource
{
    /*
     * Transform string to array of integers
     */
    private function arrayOfIntsFromString($string): array
    {
        return $string === null ? [] : array_map('intval', explode(",", $string));
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $pump = Pump::find($this->pump_id);
        $coefficients = PumpCoefficientsHelper::coefficientsForPump($pump, $this->pumps_count - $this->reserve_pumps_count);
        $intersectionPoint = IntersectionPoint::by($coefficients, $this->flow, $this->head);
        $pumpPerformance = PumpPerformance::by($pump->performance);

        $performanceLines = [];
        $yMax = 0;
        $systemPerformance = [];
        for ($currentPumpsCount = 1; $currentPumpsCount <= $this->pumps_count; ++$currentPumpsCount) {
            if ($currentPumpsCount === $this->pumps_count - $this->reserve_pumps_count) {
                $systemPerformance = SystemPerformance::by($intersectionPoint, $this->flow, $this->head)->asLineData();
            }
            $coefficients = PumpCoefficientsHelper::coefficientsForPump($pump, $currentPumpsCount);
            $performanceLineData = $pumpPerformance->asPerformanceLineData(
                $currentPumpsCount,
                Regression::withCoefficients([$coefficients->k, $coefficients->b, $coefficients->c])
            );
            $performanceLines[] = $performanceLineData['line'];
            if ($performanceLineData['yMax'] > $yMax) {
                $yMax = $performanceLineData['yMax'];
            }
        }

        return [
            'id' => $this->id,
            'head' => $this->head,
            'flow' => $this->flow,
            'fluid_temperature' => $this->fluid_temperature,
            'deviation' => $this->deviation,
            'reserve_pumps_count' => $this->reserve_pumps_count,
            'selected_pump_name' => $this->selected_pump_name,
            'use_additional_filters' => $this->use_additional_filters,
            'range_id' => $this->range_id,

            'power_limit_checked' => $this->power_limit_checked,
            'power_limit_condition_id' => $this->power_limit_condition_id,
            'power_limit_value' => $this->power_limit_value,

            'ptp_length_limit_checked' => $this->ptp_length_limit_checked,
            'ptp_length_limit_condition_id' => $this->ptp_length_limit_condition_id,
            'ptp_length_limit_value' => $this->ptp_length_limit_value,

            'dn_suction_limit_checked' => $this->dn_suction_limit_checked,
            'dn_suction_limit_condition_id' => $this->dn_suction_limit_condition_id,
            'dn_suction_limit_id' => $this->dn_suction_limit_id,

            'dn_pressure_limit_checked' => $this->dn_pressure_limit_checked,
            'dn_pressure_limit_condition_id' => $this->dn_pressure_limit_condition_id,
            'dn_pressure_limit_id' => $this->dn_pressure_limit_id,

            'connection_types' => $this->arrayOfIntsFromString($this->connection_type_ids),
            'power_adjustments' => $this->arrayOfIntsFromString($this->power_adjustment_ids),
            'main_pumps_counts' => $this->arrayOfIntsFromString($this->main_pumps_counts),
            'pump_brands' => $this->arrayOfIntsFromString($this->pump_brand_ids),
            'mains_connections' => $this->arrayOfIntsFromString($this->mains_connection_ids),
            'pump_types' => $this->arrayOfIntsFromString($this->pump_type_ids),
            'pump_applications' => $this->arrayOfIntsFromString($this->pump_application_ids),
            'custom_range' => $this->arrayOfIntsFromString($this->custom_range),

            'to_show' => [
                'name' => $this->selected_pump_name,
                'intersectionPoint' => [
                    'x' => round($intersectionPoint->x(), 1),
                    'y' => round($intersectionPoint->y(), 1),
                ],
                'pump_id' => $this->pump_id,
                'pumps_count' => $this->pumps_count,
                'systemPerformance' => $systemPerformance,
                'yMax' => $yMax,
                'lines' => $performanceLines,
                'pump_info' => [
                    'article_num_main' => $pump->article_num_main, //
                    'article_num_reserve' => $pump->article_num_reserve, //
                    'article_num_archive' => $pump->article_num_archive, //
                    'full_name' => $pump->full_name, //
                    'weight' => $pump->weight, //
                    'rated_power' => $pump->rated_power, //
                    'rated_current' => $pump->rated_current, //
                    'connection_type' => $pump->connection_type->name, //
                    'fluid_temp_min' => $pump->fluid_temp_min, //
                    'fluid_temp_max' => $pump->fluid_temp_max, //
                    'ptp_length' => $pump->ptp_length, //
                    'dn_suction' => $pump->dn_suction->value, //
                    'dn_pressure' => $pump->dn_pressure->value, //
                    'category' => $pump->series->category->name, //
                    'power_adjustment' => $pump->series->power_adjustment->name, //
                    'connection' => $pump->connection->full_value, //
                    'applications' => $pump->applications, //
                    'types' => $pump->types, //
                    'description' => $pump->description,
                    'images' => [
                        'pump' => StorageMediaHelper::getImage($pump->image),
                        'sizes' => StorageMediaHelper::getImage($pump->sizes_image),
                        'electric_diagram' => StorageMediaHelper::getImage($pump->electric_diagram_image),
                        'cross_sectional_drawing' => StorageMediaHelper::getImage($pump->cross_sectional_drawing_image),
                    ],
                    'files' => $pump->files
                        ->map(fn($file) => StorageMediaHelper::getFile($file->file_name))
                        ->filter(fn($file) => $file != null)
                        ->map(fn($file) => [
                            'name' => basename($file),
                            'link' => $file
                        ])
                ]
            ],
        ];
    }
}
