<?php

namespace App\Actions;

use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Models\LimitCondition;
use App\Models\Pumps\Pump;
use App\Support\Selections\IntersectionPoint;
use App\Support\Selections\PumpPerformance;
use App\Support\Selections\Regression;
use Illuminate\Http\JsonResponse;

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

    public function execute(array $validated)
    {
        $dbPumps = Pump::with(['series', 'series.discount'])
            ->with('currency')
            ->with(['producer', 'producer.discount'])
            ->with(['coefficients' => function ($query) use ($validated) {
                $query->whereBetween(
                    'count',
                    [1, max($validated['main_pumps_counts']) + $validated['backup_pumps_count']]
                );
            }])
            ->whereIn('series_id', $validated['series_ids']);

        // PHASES
        if ($validated['current_phase_ids'] && count($validated['current_phase_ids']) > 0) {
            $dbPumps = $dbPumps->whereIn('phase_id', $validated['current_phase_ids']);
        }

        // CONNECTION TYPES
        if ($validated['connection_type_ids'] && count($validated['connection_type_ids']) > 0) {
            $dbPumps = $dbPumps->whereIn('connection_type_id', $validated['current_phase_ids']);
        }

        // DNS
        $dnInputLimit = false;
        $dnOutputLimit = false;

        if ($validated['dn_input_limit_checked']
            && $validated['dn_input_limit_condition_id']
            && $validated['dn_input_limit_id']
        ) {
            $dnInputLimit = true;
            $dbPumps = $dbPumps->with(['dn_input' => function ($query) use ($validated) {
                $query->where(
                    'id',
                    LimitCondition::find($validated['dn_input_limit_condition_id'])->value,
                    $validated['dn_input_limit_id']
                );
            }]);
        }

        if ($validated['dn_output_limit_checked']
            && $validated['dn_output_limit_condition_id']
            && $validated['dn_output_limit_id']
        ) {
            $dnOutputLimit = true;
            $dbPumps = $dbPumps->with(['dn_output' => function ($query) use ($validated) {
                $query->where(
                    'id',
                    LimitCondition::find($validated['dn_output_limit_condition_id'])->value,
                    $validated['dn_output_limit_id']
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
        if ($validated['power_limit_checked']
            && $validated['power_limit_condition_id']
            && $validated['power_limit_value']
        ) {
            $dbPumps = $dbPumps->where(
                'power',
                LimitCondition::find($validated['power_limit_condition_id'])->value,
                $validated['power_limit_value']
            );
        }

        // BETWEEN AXES DISTANCE LIMIT
        if ($validated['between_axes_limit_checked']
            && $validated['between_axes_limit_condition_id']
            && $validated['between_axes_limit_value']
        ) {
            $dbPumps = $dbPumps->where(
                'between_axes_dist',
                LimitCondition::find($validated['between_axes_limit_condition_id'])->value,
                $validated['between_axes_limit_value']
            );
        }

        $pressure = $validated['pressure'];
        $consumption = $validated['consumption'];
        $backupPumpsCount = $validated['backup_pumps_count'];
        $limit = $validated['limit'] ?? 0;

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
            foreach ($validated['main_pumps_counts'] as $mainPumpsCount) {
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
            return response()->json(['info', 'Насосов не найдено']);
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
                $pump_rub_price_with_discount = $pump_rub_price - ($pump->series->discount->value
                        ? $pump_rub_price * $pump->series->discount->value / 100
                        : ($pump->producer->discount->value
                            ? $pump_rub_price * $pump->producer->discount->value / 100
                            : 0
                        )
                    );

                $selectedPumps[] = [
                    'key' => $num++,
                    'pump_id' => $pump->id,
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
