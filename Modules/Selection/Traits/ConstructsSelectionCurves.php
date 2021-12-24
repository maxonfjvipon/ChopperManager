<?php


namespace Modules\Selection\Traits;

use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Support\IIntersectionPoint;
use Modules\Selection\Support\Point;
use Modules\Selection\Support\PumpPerformance\PPumpPerformance;
use Modules\Selection\Support\SSystemPerformance;

trait ConstructsSelectionCurves
{
    use HasAxisStep;

    protected function selectionCurvesData(Selection $selection): array
    {
        return match ($selection->pump_type) {
            Pump::$SINGLE_PUMP => $this->singlePumpSelectionCurvesData($selection),
            Pump::$DOUBLE_PUMP => $this->doublePumpSelectionCurvesData($selection),
        };
    }

    private function singlePumpSelectionCurvesData(Selection $selection): array
    {
        return $this->selectionByPreferencesCurvesData(
            $selection->pump,
            $selection->flow,
            $selection->head,
            $selection->pumps_count - $selection->reserve_pumps_count,
            $selection->pumps_count
        );
    }

    private function doublePumpSelectionCurvesData(Selection $selection): array
    {
        return $this->selectionByPreferencesCurvesData(
            $selection->pump,
            $selection->flow,
            $selection->head,
            $selection->dp_work_scheme_id,
            2
        );
    }

    private function selectionByPreferencesCurvesData($pump, $flow, $head, $lastPumpNum, $pumpsCount): array
    {
        $pumpPerformance = PPumpPerformance::construct($pump);
        $lines = $pumpPerformance->asRegressedLines($pumpsCount);

        $yMax = $pumpPerformance->hMax($head);
        $lastLine = $lines[count($lines) - 1];
        $xMax = max($lastLine[count($lastLine) - 1]['x'], $flow + 2 * $this->axisStep($flow));
        $data = [
            'performance_lines' => $lines,
            'dx' => 500 / $xMax,
            'dy' => 330 / $yMax,
            'x_axis_step' => $this->axisStep($xMax),
            'y_axis_step' => $this->axisStep($yMax),
        ];
        if ($flow !== null && $head !== null) {
            $intersectionPoint = new IIntersectionPoint(
                $pumpPerformance->coefficientsForPosition($lastPumpNum), $flow, $head
            );
            $data = array_merge($data, [
                'system_performance' => (new SSystemPerformance($flow, $head))
                    ->asXYArrayData(
                        max($intersectionPoint->y(), $head),
                        $intersectionPoint->x() < 50 ? 0.5 : 1
                    ),
                'working_point' => [
                    'flow' => $flow,
                    'head' => $head,
                ],
                'intersection_point' => [
                    'flow' => $intersectionPoint->x(),
                    'head' => $intersectionPoint->y(),
                ],
            ]);
        }
        return $data;
    }
}
