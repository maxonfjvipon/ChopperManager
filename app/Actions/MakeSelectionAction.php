<?php

namespace App\Actions;

use App\Http\Requests\MakeSelectionRequest;
use App\Models\LimitCondition;
use App\Models\Pumps\Pump;
use App\Support\Pumps\PumpCoefficientsHelper;
use App\Support\Rates;
use App\Support\Selections\IntersectionPoint;
use App\Support\Selections\PumpPerformance;
use App\Support\Selections\Regression;
use App\Support\Selections\SystemPerformance;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MakeSelectionAction
{
    public function execute(MakeSelectionRequest $request): JsonResponse
    {
        ini_set('memory_limit', '200M');
        $dbPumps = Pump
            ::whereIn('series_id', $request->series_ids)
            ->with(['series', 'series.discounts' => function ($query) {
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
            }]);

        // MAINS CONNECTIONS
        if ($request->mains_connection_ids && count($request->mains_connection_ids) > 0) {
            $dbPumps = $dbPumps->whereIn('connection_id', $request->mains_connection_ids);
        }

        // CONNECTION TYPES
        if ($request->connection_type_ids && count($request->connection_type_ids) > 0) {
            $dbPumps = $dbPumps->whereIn('connection_type_id', $request->connection_type_ids);
        }

        // DNS
        $dnSuctionLimit = false;
        $dnPressureLimit = false;

        if ($request->dn_suction_limit_checked
            && $request->dn_suction_limit_condition_id
            && $request->dn_suction_limit_id
        ) {
            $dnSuctionLimit = true;
            $dbPumps = $dbPumps->whereRelation('dn_suction',
                'id',
                LimitCondition::find($request->dn_suction_limit_condition_id)->value,
                $request->dn_suction_limit_id
            );
        }

        if ($request->dn_pressure_limit_checked
            && $request->dn_pressure_limit_condition_id
            && $request->dn_pressure_limit_id
        ) {
            $dnPressureLimit = true;
            $dbPumps = $dbPumps->whereRelation('dn_pressure',
                'id',
                LimitCondition::find($request->dn_pressure_limit_condition_id)->value,
                $request->dn_pressure_limit_id
            );
        }

        // DNS x2
        if (!$dnSuctionLimit) {
            $dbPumps = $dbPumps->with('dn_suction');
        }
        if (!$dnPressureLimit) {
            $dbPumps = $dbPumps->with('dn_pressure');
        }

        // POWER deviation
        if ($request->power_limit_checked
            && $request->power_limit_condition_id
            && $request->power_limit_value
        ) {
            $dbPumps = $dbPumps->where(
                'rated_power',
                LimitCondition::find($request->power_limit_condition_id)->value,
                $request->power_limit_value
            );
        }

        // PTP LENGTH DISTANCE deviation
        if ($request->ptp_length_limit_checked
            && $request->ptp_length_limit_condition_id
            && $request->ptp_length_limit_value
        ) {
            $dbPumps = $dbPumps->where(
                'ptp_length',
                LimitCondition::find($request->ptp_length_limit_condition_id)->value,
                $request->ptp_length_limit_value
            );
        }

        $head = $request->head;
        $flow = $request->flow;

        $reservePumpsCount = $request->reserve_pumps_count;
        $deviation = $request->deviation ?? 0;

        $rates = new Rates(Auth::user()->currency->code);
        $selectedPumps = [];
        $defaultSystemPerformance = null;
        $num = 1;

        $dbPumps = $dbPumps->get([
            'id', 'article_num_main', 'series_id', 'currency_id', 'dn_suction_id',
            'dn_pressure_id', 'price', 'name', 'rated_power', 'ptp_length', 'performance'
        ]);

        foreach ($dbPumps as $pump) {
            $pumpPerformance = PumpPerformance::by($pump->performance);
            $performanceAsArray = $pumpPerformance->asArray();
            $performanceAsArrayLength = count($performanceAsArray);
            foreach ($request->main_pumps_counts as $mainPumpsCount) {
                $qEnd = $performanceAsArray[$performanceAsArrayLength - 2] * $mainPumpsCount;
                if ($flow >= $qEnd) {
                    continue;
                }
                $coefficients = PumpCoefficientsHelper::coefficientsForPump($pump, $mainPumpsCount);
                if ($head > ($coefficients->k * $flow * $flow + $coefficients->b * $flow + $coefficients->c)) {
                    continue;
                }
                $qStart = $performanceAsArray[0] * $mainPumpsCount;
                $intersectionPoint = IntersectionPoint::by($coefficients, $flow, $head);

                // pump with current main pumps count is appropriate
                if ($intersectionPoint->x() <= $qEnd && $intersectionPoint->x() >= $qStart
                    && $intersectionPoint->y() >= $head - $head * $deviation / 100
                ) {
                    $pumpsCount = $mainPumpsCount + $reservePumpsCount;
                    $pump_price = $pump->currency->code === $rates->base()
                        ? $pump->price
                        : round($pump->price / $rates->rate($pump->currency->code), 2);
                    $pump_price_with_discount = $pump_price - $pump_price * $pump->series->discounts[0]->value / 100;
                    $performanceLines = [];
                    $yMax = 0;
                    $systemPerformance = [];
                    for ($currentPumpsCount = 1; $currentPumpsCount <= $pumpsCount; ++$currentPumpsCount) {
                        if ($currentPumpsCount === $mainPumpsCount) {
                            $systemPerformance = SystemPerformance::by($intersectionPoint, $flow, $head)->asLineData();
                            if (!$defaultSystemPerformance) {
                                $defaultSystemPerformance = $systemPerformance;
                            }
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

                    $selectedPumps[] = [
                        'key' => $num++,
                        'pumps_count' => $pumpsCount,
                        'name' => $pumpsCount . ' ' . $pump->brand->name . ' ' . $pump->series->name . ' ' . $pump->name,
                        'pump_id' => $pump->id,
                        'articleNum' => $pump->article_num_main,
                        'retailPrice' => $pump_price,
                        'discountedPrice' => round($pump_price_with_discount, 2),
                        'retailPriceSum' => round($pump_price * $pumpsCount, 2),
                        'discountedPriceSum' => round($pump_price_with_discount * $pumpsCount, 2),
                        'dnSuction' => $pump->dn_suction->value,
                        'dnPressure' => $pump->dn_pressure->value,
                        'rated_power' => $pump->rated_power,
                        'powerSum' => round($pump->rated_power * $pumpsCount, 1),
                        'ptpLength' => $pump->ptp_length,
                        'intersectionPoint' => [
                            'x' => round($intersectionPoint->x(), 1),
                            'y' => round($intersectionPoint->y(), 1),
                        ],
                        'systemPerformance' => $systemPerformance,
                        'yMax' => $yMax,
                        'lines' => $performanceLines
                    ];
                }
            }
        }

        if (count($selectedPumps) === 0) {
            return response()->json(['info' => __('flash.selections.pumps_not_found')]);
        }

//        dd(memory_get_peak_usage(), memory_get_usage());

        return response()->json([
            'selected_pumps' => $selectedPumps,
            'working_point' => [
                'x' => $flow,
                'y' => $head
            ],
            'default_system_performance' => $defaultSystemPerformance
        ]);
    }
}
