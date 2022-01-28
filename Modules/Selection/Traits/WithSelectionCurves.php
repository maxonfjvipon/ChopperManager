<?php

namespace Modules\Selection\Traits;

use Modules\Selection\Support\IIntersectionPoint;
use Modules\Selection\Support\PumpPerformance\PPumpPerformance;
use Modules\Selection\Support\SSystemPerformance;

/**
 * Selection curves.
 * @package Modules\Selection\Traits
 */
trait WithSelectionCurves
{
    use HasAxisStep;

    public function withCurves(): self
    {
        $pumpPerformance = PPumpPerformance::construct($this->pump);
        $lines = $pumpPerformance->asRegressedLines($this->pumps_count ?? 2);

        $yMax = $pumpPerformance->hMax($this->head);
        $lastLine = $lines[count($lines) - 1];
        $xMax = max($lastLine[count($lastLine) - 1]['x'], $this->flow + 2 * $this->axisStep($this->flow));
        $data = [
            'performance_lines' => $lines,
            'dx' => 500 / $xMax,
            'dy' => 330 / $yMax,
            'x_axis_step' => $this->axisStep($xMax),
            'y_axis_step' => $this->axisStep($yMax),
        ];
        if ($this->flow !== null && $this->head !== null) {
            $intersectionPoint = new IIntersectionPoint(
                $pumpPerformance->coefficientsForPosition(
                    $this->dp_work_scheme_id ?? ($this->pumps_count - $this->reserve_pumps_count)
                ), $this->flow, $this->head
            );
            $data = array_merge($data, [
                'system_performance' => (new SSystemPerformance($this->flow, $this->head))
                    ->asXYArrayData(
                        max($intersectionPoint->y(), $this->head),
                        $intersectionPoint->x() < 50 ? 0.5 : 1
                    ),
                'working_point' => [
                    'flow' => $this->flow,
                    'head' => $this->head,
                ],
                'intersection_point' => [
                    'flow' => $intersectionPoint->x(),
                    'head' => $intersectionPoint->y(),
                ],
            ]);
        }
        $this->{'curves_data'} = $data;
        return $this;
    }
}
