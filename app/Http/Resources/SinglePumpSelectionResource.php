<?php

namespace App\Http\Resources;

use App\Models\Pumps\Pump;
use App\Support\Pumps\PumpCoefficientsHelper;
use App\Support\Selections\IntersectionPoint;
use App\Support\Selections\PumpPerformance;
use App\Support\Selections\Regression;
use App\Support\Selections\SystemPerformance;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'pump_producers' => $this->arrayOfIntsFromString($this->pump_producer_ids),
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
                'lines' => $performanceLines
            ],
        ];
    }
}
