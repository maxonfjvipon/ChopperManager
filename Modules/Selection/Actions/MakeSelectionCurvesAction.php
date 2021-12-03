<?php

namespace Modules\Selection\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Http\Requests\CurvesForSelectionRequest;
use Modules\Selection\Support\IIntersectionPoint;
use Modules\Selection\Support\PPumpPerformance;
use Modules\Selection\Support\SSystemPerformance;
use Modules\Selection\Traits\HasAxisStep;

class MakeSelectionCurvesAction
{
    use HasAxisStep;

    public function selectionPerfCurvesData($pump, $mpc, $pc, $flow, $head): array
    {
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

    public function execute(CurvesForSelectionRequest $request): JsonResponse
    {
        return response()->json(
            view('selection::selection_perf_curves', $this->selectionPerfCurvesData(
                Pump::find($request->pump_id),
                $request->main_pumps_count,
                $request->pumps_count,
                $request->flow,
                $request->head,
            ))->render()
        );
    }
}
