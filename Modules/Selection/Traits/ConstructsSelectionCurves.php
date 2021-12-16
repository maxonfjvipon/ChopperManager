<?php


namespace Modules\Selection\Traits;

use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Support\IIntersectionPoint;
use Modules\Selection\Support\PPumpPerformance;
use Modules\Selection\Support\SSystemPerformance;

trait ConstructsSelectionCurves
{
    use HasAxisStep;

    protected function selectionCurvesData(Selection $selection): array
    {
        return match($selection->pump_type) {
            Pump::$SINGLE_PUMP => $this->singlePumpSelectionCurvesData($selection),
            Pump::$DOUBLE_PUMP => $this->doublePumpSelectionCurvesData($selection),
        };
    }

    private function singlePumpSelectionCurvesData(Selection $selection): array
    {
        $pump = $selection->pump;
        $mpc = $selection->pumps_count - $selection->reserve_pumps_count;
        $pc = $selection->pumps_count;
        $flow = $selection->flow;
        $head = $selection->head;

        $pumpPerformance = new PPumpPerformance($pump);
        $intersectionPoint = new IIntersectionPoint(
            $pumpPerformance->coefficientsForPosition($mpc), $flow, $head
        );
        $lines = $pumpPerformance->asRegressedLines($pc);

        $yMax = $pumpPerformance->hMax($head);
        $lastLine = $lines[count($lines) - 1];
        $xMax = max($lastLine[count($lastLine) - 1]['x'], $flow + 2 * $this->axisStep($flow));

        return [
            'working_point' => [
                'flow' => $flow,
                'head' => $head,
            ],
            'intersection_point' => [
                'flow' => $intersectionPoint->x(),
                'head' => $intersectionPoint->y(),
            ],
            'performance_lines' => $lines,
            'system_performance' => (new SSystemPerformance($flow, $head))
                ->asXYArrayData(
                    max($intersectionPoint->y(), $head),
                    $intersectionPoint->x() < 50 ? 0.5 : 1
                ),
            'dx' => 500 / $xMax,
            'dy' => 330 / $yMax,
            'x_axis_step' => $this->axisStep($xMax),
            'y_axis_step' => $this->axisStep($yMax),
        ];
    }

    private function doublePumpSelectionCurvesData(Selection $selection): array
    {

    }
}
