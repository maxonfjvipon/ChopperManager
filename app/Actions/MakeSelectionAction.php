<?php

namespace App\Actions;

use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Http\Requests\MakeSelectionRequest;
use App\Models\LimitCondition;
use App\Models\Pumps\Pump;
use App\Support\Selections\IntersectionPoint;
use App\Support\Selections\PumpPerformance;
use App\Support\Selections\Regression;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MakeSelectionAction
{
    private function systemPerformance($intersectionPoint, $pressure, $consumption): array
    {
        $systemPerformance = [];
        for ($q = 0.2; $q < $intersectionPoint['x'] + 0.4; $q += 0.2) {
            $systemPerformance[] = [
                'x' => round($q, 1),// to fixed 1
                'y' => round($pressure / ($consumption * $consumption) * $q * $q, 1) // to fixed 1
            ];
        }
        return $systemPerformance;
    }

    public function execute(MakeSelectionRequest $request): JsonResponse
    {
        $dbPumps = Pump
            ::with(['series', 'series.discounts' => function ($query) {
                $query->where('user_id', Auth::id());
            }])
            ->with('currency')
            ->with(['brand', 'brand.discounts' => function ($query) {
                $query->where('user_id', Auth::id());
            }])
            ->with(['coefficients' => function ($query) use ($request) {
                $query->whereBetween(
                    'position',
                    [1, max($request->main_pumps_counts) + $request->reserve_pumps_count]
                );
            }])
            ->whereIn('series_id', $request->series_ids);

        // TODO: from here
        // PHASES
        if ($request->current_phase_ids && count($request->current_phase_ids) > 0) {
            $dbPumps = $dbPumps->whereIn('phase_id', $request->current_phase_ids);
        }

        // CONNECTION TYPES
        if ($request->connection_type_ids && count($request->connection_type_ids) > 0) {
            $dbPumps = $dbPumps->whereIn('connection_type_id', $request->current_phase_ids);
        }

        // DNS
        $dnInputLimit = false;
        $dnOutputLimit = false;

        if ($request->dn_input_limit_checked
            && $request->dn_input_limit_condition_id
            && $request->dn_input_limit_id
        ) {
            $dnInputLimit = true;
            $dbPumps = $dbPumps->with(['dn_input' => function ($query) use ($request) {
                $query->where(
                    'id',
                    LimitCondition::find($request->dn_input_limit_condition_id)->value,
                    $request->dn_input_limit_id
                );
            }]);
        }

        if ($request->dn_output_limit_checked
            && $request->dn_output_limit_condition_id
            && $request->dn_output_limit_id
        ) {
            $dnOutputLimit = true;
            $dbPumps = $dbPumps->with(['dn_output' => function ($query) use ($validated) {
                $query->where(
                    'id',
                    LimitCondition::find($request->dn_output_limit_condition_id)->value,
                    $request->dn_output_limit_id
                );
            }]);
        }

        // DNS 2
        if (!$dnInputLimit) {
            $dbPumps = $dbPumps->with('dn_input');
        }
        if (!$dnOutputLimit) {
            $dbPumps = $dbPumps->with('dn_output');
        }

        // POWER LIMIT
        if ($request->power_limit_checked
            && $request->power_limit_condition_id
            && $request->power_limit_value
        ) {
            $dbPumps = $dbPumps->where(
                'power',
                LimitCondition::find($request->power_limit_condition_id)->value,
                $request->power_limit_value
            );
        }

        // BETWEEN AXES DISTANCE LIMIT
        if ($request->between_axes_limit_checked
            && $request->between_axes_limit_condition_id
            && $request->between_axes_limit_value
        ) {
            $dbPumps = $dbPumps->where(
                'between_axes_dist',
                LimitCondition::find($request->between_axes_limit_condition_id)->value,
                $request->between_axes_limit_value
            );
        }

        $pressure = $request->pressure;
        $consumption = $request->consumption;
        $backupPumpsCount = $request->backup_pumps_count;
        $limit = $request->limit ?? 0;

        $rates = Currency::rates()
            ->latest()
            ->symbols(\App\Models\Currency::all()->pluck('name')->all())
            ->base('RUB')
            ->amount(1)
            ->round(5) // TODO: find optimal round
            ->get();

        $selectedPumps = [];
        $num = 1;

        $dbPumps = $dbPumps->get([
            'id', 'part_num_main', 'series_id', 'currency_id', 'dn_input_id',
            'dn_output_id', 'price', 'name', 'power', 'between_axes_dist', 'performance'
        ])->transform(function ($pump) use ($validated, $pressure, $consumption, $backupPumpsCount, $limit) {
            $performanceAsArray = (new PumpPerformance($pump->performance))->asArray();
            $pumpCountsAndIntersectionPoints = [];
            foreach ($request->main_pumps_counts as $mainPumpsCount) {
                $coefficients = $pump->coefficients->firstWhere('count', $mainPumpsCount);
                $qStart = $performanceAsArray[0] * $mainPumpsCount;
                $qEnd = $performanceAsArray[(count($performanceAsArray) - 2)] * $mainPumpsCount;
                $intersectionPoint = new IntersectionPoint(
                    [$coefficients->k, $coefficients->b, $coefficients->c],
                    $pressure,
                    $consumption
                );
                $x = $intersectionPoint->x();
                $y = $intersectionPoint->y();

                // appropriate
                if (round($y, 2) >= $pressure - round($pressure * $limit / 100, 2) && $x >= $qStart && $x <= $qEnd) {
                    $pumpCountsAndIntersectionPoints[] = [
                        'mainPumpsCount' => $mainPumpsCount,
                        'intersectionPoint' => [
                            'x' => $x,
                            'y' => $y
                        ]
                    ];
                }
            }
            $pump['pumpCountsAndIntersectionPoints'] = $pumpCountsAndIntersectionPoints;
            return $pump;
        })->filter(function ($pump) {
            return count($pump->pumpCountsAndIntersectionPoints) > 0;
        });

//        return response()->json([
//            'selected_pumps' => $dbPumps
//        ]);

        if (count($dbPumps) === 0) {
            return response()->json(['info', __('flash.selections.pumps_not_found')]);
        }

        $dbPumps->map(function ($pump) use ($backupPumpsCount, $rates, $pressure, $consumption, &$num, &$selectedPumps) {
            $performance = new PumpPerformance($pump->performance);
            foreach ($pump->pumpCountsAndIntersectionPoints as $pumpCountAndIntersectionPoint) {
                $pumpsCount = $pumpCountAndIntersectionPoint['mainPumpsCount'] + $backupPumpsCount;
                $yMax = 0;
                $performanceLines = [];
                $systemPerformance = [];
                for ($currentPumpsCount = 1; $currentPumpsCount <= $pumpsCount; ++$currentPumpsCount) {
                    if ($currentPumpsCount === $pumpCountAndIntersectionPoint['mainPumpsCount']) {
                        $systemPerformance = $this->systemPerformance($pumpCountAndIntersectionPoint['intersectionPoint'], $pressure, $consumption);
                    }
                    $coefficients = $pump->coefficients->firstWhere('count', $currentPumpsCount);
                    $performanceLineData = $performance->asPerformanceLineData(
                        $currentPumpsCount,
                        Regression::withCoefficients([$coefficients->k, $coefficients->b, $coefficients->c])
                    );
                    $performanceLines[] = $performanceLineData['line'];
                    if ($performanceLineData['yMax'] > $yMax) {
                        $yMax = $performanceLineData['yMax'];
                    }
                }
                $pump_rub_price = $pump->currency->name === 'RUB'
                    ? $pump->price
                    : round($pump->price / $rates[$pump->currency->name], 2);
                $pump_rub_price_with_discount = $pump_rub_price -
                    (count($pump->series->discounts) === 1
                        ? $pump_rub_price * $pump->series->discounts[0]->value / 100
                        : (count($pump->producer->discounts) === 1
                            ? $pump_rub_price * $pump->producer->discounts[0]->value / 100
                            : 0
                        )
                    );

                $selectedPumps[] = [
                    'key' => $num++,
                    'pumps_count' => $pumpsCount,
                    'name' => $pumpsCount . ' ' . $pump->producer->name . ' ' . $pump->series->name . ' ' . $pump->name,
                    'partNum' => $pump->part_num_main,
                    'retailPrice' => $pump_rub_price,
                    'personalPrice' => round($pump_rub_price_with_discount, 2),
                    'retailPriceSum' => round($pump_rub_price * $pumpsCount, 2),
                    'personalPriceSum' => round($pump_rub_price_with_discount * $pumpsCount, 2),
                    'dnInput' => $pump->dn_input->value,
                    'dnOutput' => $pump->dn_output->value,
                    'power' => $pump->power,
                    'powerSum' => round($pump->power * $pumpsCount, 1),
                    'betweenAxesDist' => $pump->between_axes_dist,
                    'intersectionPoint' => [
                        'x' => $pumpCountAndIntersectionPoint['intersectionPoint']['x'],
                        'y' => $pumpCountAndIntersectionPoint['intersectionPoint']['y']
                    ],
                    'systemPerformance' => $systemPerformance,
                    'yMax' => $yMax,
                    'lines' => $performanceLines
                ];
            }
        });

        return response()->json([
            'selected_pumps' => $selectedPumps,
            'working_point' => [
                'x' => $consumption,
                'y' => $pressure
            ]
        ]);
    }
}
