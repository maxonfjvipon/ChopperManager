<?php

namespace Modules\Selection\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Support\Rates;
use Modules\Core\Support\TenantStorage;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Support\PumpCoefficientsHelper;
use Modules\Selection\Entities\LimitCondition;
use Modules\Selection\Entities\SelectionRange;
use Modules\Selection\Http\Requests\MakeSinglePumpSelectionRequest;
use Modules\Selection\Support\IIntersectionPoint;
use Modules\Selection\Support\IntersectionPoint;
use Modules\Selection\Support\PPumpPerformance;
use Modules\Selection\Support\PumpPerformance;

class MakeSelectionAction
{
    private function executeEloquent(MakeSinglePumpSelectionRequest $request): JsonResponse
    {
        ini_set('memory_limit', '200M');
        $dbPumps = Pump
            ::whereIn('series_id', $request->series_ids)
            ->with(['series', 'series.discounts' => function ($query) {
                $query->where('user_id', Auth::id());
            }, 'series.applications', 'series.types', 'series.category', 'series.power_adjustment'])
            ->with(['price_lists' => function ($query) {
                $query->where('country_id', Auth::user()->country_id);
            }, 'price_lists.currency'])
            ->with(['brand', 'brand.discounts' => function ($query) {
                $query->where('user_id', Auth::id());
            }])
            ->with('connection_type')
            ->with('connection')
            ->with('files')
            ->with(['coefficients' => function ($query) use ($request) {
                $query->whereBetween(
                    'position',
                    [1, max($request->main_pumps_counts) + $request->reserve_pumps_count]
                );
            }]);

        // DNS
        $dnSuctionLimit = false;
        $dnPressureLimit = false;

        // ADDITIONAL FILTERS
        if ($request->use_additional_filters) {
            // MAINS CONNECTIONS
            if ($request->mains_connection_ids && count($request->mains_connection_ids) > 0) {
                $dbPumps = $dbPumps->whereIn('pumps.connection_id', $request->mains_connection_ids);
            }

            // CONNECTION TYPES
            if ($request->connection_type_ids && count($request->connection_type_ids) > 0) {
                $dbPumps = $dbPumps->whereIn('pumps.connection_type_id', $request->connection_type_ids);
            }

            // DN SUCTION LIMITING
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

            // DN PRESSURE LIMITING
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

            // POWER LIMITING
            if ($request->power_limit_checked
                && $request->power_limit_condition_id
                && $request->power_limit_value
            ) {
                $dbPumps = $dbPumps->where(
                    'pumps.rated_power',
                    LimitCondition::find($request->power_limit_condition_id)->value,
                    $request->power_limit_value
                );
            }

            // PTP LENGTH DISTANCE LIMITING
            if ($request->ptp_length_limit_checked
                && $request->ptp_length_limit_condition_id
                && $request->ptp_length_limit_value
            ) {
                $dbPumps = $dbPumps->where(
                    'pumps.ptp_length',
                    LimitCondition::find($request->ptp_length_limit_condition_id)->value,
                    $request->ptp_length_limit_value
                );
            }
        }

        // DNS
        if (!$dnSuctionLimit) {
            $dbPumps = $dbPumps->with('dn_suction');
        }
        if (!$dnPressureLimit) {
            $dbPumps = $dbPumps->with('dn_pressure');
        }

        $head = $request->head;
        $flow = $request->flow;

        $reservePumpsCount = $request->reserve_pumps_count;
        $deviation = $request->deviation ?? 0;

        $rates = new Rates(Auth::user()->currency->code);
        $selectedPumps = [];
        $defaultSystemPerformance = null;
        $num = 1;
        $range = SelectionRange::find($request->range_id);

        $dbPumps = $dbPumps->get([
            'id', 'article_num_main', 'article_num_reserve', 'article_num_archive', 'series_id', 'dn_suction_id',
            'dn_pressure_id', 'name', 'rated_power', 'rated_current', 'ptp_length', 'performance', 'weight',
            'image', 'sizes_image', 'cross_sectional_drawing_image', 'electric_diagram_image', 'connection_type_id',
            'fluid_temp_min', 'fluid_temp_max', 'connection_id', 'description'
        ]);

        $tenantStorage = new TenantStorage();

        foreach ($dbPumps as $pump) {
            if (count($pump->price_lists) === 1) { // FIXME ???
//                $pumpPerformance = new PumpPerformance($pump->performance);
                $pumpPerformance = new PPumpPerformance($pump);
//                $performanceAsArray = $pumpPerformance->asArray();
//                $performanceAsArrayLength = count($performanceAsArray);
                foreach ($request->main_pumps_counts as $mainPumpsCount) {
                    $qEnd = $pumpPerformance->qEnd($mainPumpsCount);
//                    $qEnd = $performanceAsArray[$performanceAsArrayLength - 2] * $mainPumpsCount;
                    if ($flow >= $qEnd) {
                        continue;
                    }
                    $coefficients = $pumpPerformance->coefficientsForPosition($mainPumpsCount);
//                    $coefficients = $pump->coefficients->firstWhere('position', $mainPumpsCount);
//                    $coefficients = PumpCoefficientsHelper::coefficientsForPump($pump, $mainPumpsCount);
                    if ($head > ($coefficients->k * $flow * $flow + $coefficients->b * $flow + $coefficients->c)) {
                        continue;
                    }
                    $qStart = $pumpPerformance->qStart($mainPumpsCount);
//                    $qStart = $performanceAsArray[0] * $mainPumpsCount;
                    $intersectionPoint = new IIntersectionPoint($coefficients, $flow, $head);
//                    $intersectionPoint = IntersectionPoint::byCoefficients($coefficients, $flow, $head);

                    // range length
                    $qDist = $qEnd - $qStart;

                    // pump with current main pumps count is appropriate
                    // custom range
                    if ($request->range_id === SelectionRange::$CUSTOM) {
                        $rStart = $request->custom_range[0] / 100;
                        $rEnd = (100 - $request->custom_range[1]) / 100;
                    } else {
                        $rStart = $range->value;
                        $rEnd = $range->value;
                    }

                    if ($intersectionPoint->x() >= $qStart + $qDist * $rStart
                        && $intersectionPoint->x() <= $qEnd - $qDist * $rEnd
                        && $intersectionPoint->y() >= $head - $head * $deviation / 100
                    ) {
                        $pumpsCount = $mainPumpsCount + $reservePumpsCount;
                        $pump_price_list = $pump->price_lists[0];
                        $pump_price = $pump_price_list->currency->code === $rates->base()
                            ? $pump_price_list->price
                            : round($pump_price_list->price / $rates->rate($pump_price_list->currency->code), 2);
                        $pump_price_with_discount = $pump_price - $pump_price * $pump->series->discounts[0]->value / 100;

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
                            'head' => $head,
                            'flow' => $flow,
                            'main_pumps_count' => $mainPumpsCount,
                            'fluid_temperature' => $request->fluid_temperature,
                            'intersection_point' => [
                                'flow' => $intersectionPoint->x(),
                                'head' => $intersectionPoint->y()
                            ],
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
                                    'pump' => $tenantStorage->urlToImage($pump->image),
                                    'sizes' => $tenantStorage->urlToImage($pump->sizes_image),
                                    'electric_diagram' => $tenantStorage->urlToImage($pump->electric_diagram_image),
                                    'cross_sectional_drawing' => $tenantStorage->urlToImage($pump->cross_sectional_drawing_image),
                                ],
                                'files' => $pump->files
                                    ->map(fn($file) => $tenantStorage->urlToFile($file->file_name))
                                    ->filter(fn($file) => $file != null)
                                    ->map(fn($file) => [
                                        'name' => basename($file),
                                        'link' => $file
                                    ])
                            ],
                        ];
                    }
                }
            }
        }

        if (count($selectedPumps) === 0) {
            return response()->json(['info' => __('flash.selections.pumps_not_found')]);
        }

        return response()->json([
            'selected_pumps' => $selectedPumps,
            'working_point' => [
                'x' => $flow,
                'y' => $head
            ],
        ]);
    }

    private function executeQuery(MakeSinglePumpSelectionRequest $request)
    {
        //        $dbPumps = DB::table('pumps')
//            ->whereIn('series_id', $request->series_ids)
//            ->join('pump_series', 'pump_series.id', '=', 'pumps.series_id')
//            ->join('pump_brands', 'pump_brands.id', '=', 'pump_series.brand_id')
//            ->join('discounts series_discounts', function ($join) {
//                $join->on('pump_series.id', '=', 'series_discounts.discountable_id')
//                    ->where('series_discounts.discountable_type', '=', 'pump_series')
//                    ->and('series_discounts.user_id', '=', Auth::id());
//            })
//            ->join('discounts brand_discounts', function ($join) {
//                $join->on('pump_brands.id', '=', 'brand_discounts.discountable_id')
//                    ->where('brand_discounts.discountable_type', '=', 'pump_brand')
//                    ->and('brand_discounts.user_id', '=', Auth::id());
//            })
//            ->join('currencies', 'pumps.currency_id', '=', 'currencies.id')
//            ->join('pumps_and_coefficients', function ($join) use ($request) {
//                $join->on('pumps_and_coefficients.pump_id', '=', 'pumps.id')
//                    ->andBetween('pumps_and_coefficients.position',
//                        [1, max($request->main_pumps_counts) + $request->reserve_pumps_count]);
//            });

//        dd(Pump::whereIn('series_id', $request->series_ids)
//            ->with(['price_lists' => function ($query) {
//                $query->where('country_id', Auth::user()->country_id);
//            }, 'price_lists.currency'])->get()->all()[0]);

        //                $dbPumps = $dbPumps->join('dns dns1', function ($join) use ($request) {
//                    $join->on('dns1.id', '=', 'pumps.dn_suction_id')
//                        ->where(
//                            'dns1.id',
//                            LimitCondition::find($request->dn_suction_limit_condition_id)->value,
//                            $request->dn_suction_limit_id
//                        );
//                });
        //                $dbPumps = $dbPumps->join('dns dns2', function ($join) use ($request) {
//                    $join->on('dns2.id', '=', 'pumps.dn_pressure_id')
//                        ->where(
//                            'dns2.id',
//                            LimitCondition::find($request->dn_pressure_limit_condition_id)->value,
//                            $request->dn_pressure_limit_id
//                        );
//                });
        //            $dbPumps = $dbPumps->join('dns dns1', function ($join) use ($request) {
//                $join->on('dns1.id', '=', 'pumps.dn_suction_id');
//            });
        //            $dbPumps = $dbPumps->join('dns dns2', function ($join) use ($request) {
//                $join->on('dns2.id', '=', 'pumps.dn_pressure_id');
//            });

//        $dbPumps = $dbPumps->select(
//            'pumps.id as pump_id', 'pumps.article_num_main', 'pumps.price', 'pumps.name',
//            'pumps.rated_power', 'pumps.ptp_length', 'pumps.performance', 'pumps_and_coefficients.k as coef_k',
//            'pumps_and_coefficients.b as coef_b', 'pumps_and_coefficients.c as coef_c',
//            'pumps_and_coefficients.position as coef_position', 'pumps_brand.name as brand', 'pumps_series.name as series',
//            'brand_discounts.value as brand_discount', 'series_discounts.value as series_discount',
//            'dns1.value as dn_suction', 'dns2.value as dn_pressure'
//        );
    }


    public function execute(MakeSinglePumpSelectionRequest $request): JsonResponse
    {
        return $this->executeEloquent($request);
    }
}
