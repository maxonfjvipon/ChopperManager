<?php

namespace Modules\Selection\Endpoints;

use App\Http\Controllers\Controller;
use App\Support\FormattedPrice;
use App\Takes\TkAuthorized;
use App\Takes\TkJson;
use Illuminate\Contracts\Support\Responsable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFiltered;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Logical\EqualityOf;
use Maxonfjvipon\Elegant_Elephant\Numerable\LengthOf;
use Maxonfjvipon\Elegant_Elephant\Text\TxtImploded;
use Modules\Core\Support\Rates;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\SelectionRange;
use Modules\Selection\Http\Requests\MakeSelectionRequest;
use Modules\Selection\Support\ArrPumpsForSelecting;
use Modules\Selection\Support\IIntersectionPoint;
use Modules\Selection\Support\PumpPerformance\PPumpPerformance;
use Symfony\Component\HttpFoundation\Response;

final class SelectionsSelectEndpoint extends Controller
{
    /**
     * @param MakeSelectionRequest $request
     * @return Responsable|Response
     */
    public function __invoke(MakeSelectionRequest $request): Responsable|Response
    {
        return TkAuthorized::new(
            'selection_create',
            TkAuthorized::new(
                'selection_edit',
                TkJson::new(function () use ($request) {
                    $num = 1;
                    $range = SelectionRange::allOrCached()->find($request->range_id);
                    $rates = Rates::new();
                    $selectedPumps = ArrMerged::new(
                        ...ArrMapped::new(
                        ArrPumpsForSelecting::new($request),
                        function (Pump $pump) use ($request, &$num, $rates, $range) {
                            $performance = PPumpPerformance::construct($pump);
                            return ArrFiltered::new(
                                ArrMapped::new(
                                    $request->main_pumps_counts ?? [null],
                                    function ($mainPumpsCount) use ($pump, $request, &$num, $rates, $performance, $range) {
                                        $lastPumpPos = $mainPumpsCount ?? $request->dp_work_scheme_id;
                                        $qEnd = $performance->qEnd($lastPumpPos);
                                        if ($request->flow < $qEnd) {
                                            $qStart = $performance->qStart($lastPumpPos);
                                            $intersectionPoint = new IIntersectionPoint(
                                                $performance->coefficientsForPosition($lastPumpPos),
                                                $request->flow,
                                                $request->head
                                            );
                                            if ($request->range_id === SelectionRange::$CUSTOM) {
                                                $rStart = $request->custom_range[0] / 100;
                                                $rEnd = (100 - $request->custom_range[1]) / 100;
                                            } else {
                                                $rStart = $range->value;
                                                $rEnd = $range->value;
                                            }
                                            if ($request->flow >= $qStart
                                                && $request->flow <= $qEnd
                                                && $intersectionPoint->x() >= $qStart + ($qEnd - $qStart) * $rStart
                                                && $intersectionPoint->x() <= $qEnd - ($qEnd - $qStart) * $rEnd
                                                && $intersectionPoint->y() >= $request->head + $request->head * ($request->deviation ?? 0 / 100)
                                            ) {
                                                $pumpPrice = $pump->currentPrices($rates);
                                                $pumpsCount = $request->pumps_count
                                                    ?? $mainPumpsCount + $request->reserve_pumps_count;
                                                $countTotal = $mainPumpsCount ? $pumpsCount : 1;
                                                return [
                                                    'key' => $num++,
                                                    'pumps_count' => $pumpsCount,
                                                    'name' => TxtImploded::new(
                                                        " ",
                                                        $mainPumpsCount ? $pumpsCount : "",
                                                        $pump->brand->name,
                                                        $pump->name
                                                    )->asString(),
                                                    'pump_id' => $pump->id,
                                                    'article_num' => $pump->article_num_main,
                                                    'retail_price' => round($pumpPrice['simple'], 2),
                                                    'discounted_price' => round($pumpPrice['discounted'], 2),
                                                    'retail_price_total' => round($pumpPrice['simple'] * $countTotal, 2),
                                                    'discounted_price_total' => round($pumpPrice['discounted'] * $countTotal, 2),
                                                    'dn_suction' => $pump->dn_suction->value,
                                                    'dn_pressure' => $pump->dn_pressure->value,
                                                    'rated_power' => $pump->rated_power,
                                                    'power_total' => round($pump->rated_power * $countTotal, 4),
                                                    'ptp_length' => $pump->ptp_length,
                                                    'head' => $request->head,
                                                    'flow' => $request->flow,
                                                    'main_pumps_count' => $mainPumpsCount,
                                                    'fluid_temperature' => $request->fluid_temperature,
                                                    'dp_work_scheme_id' => $request->dp_work_scheme_id
                                                ];
                                            }
                                        }
                                    }
                                ),
                                fn($obj) => $obj != null
                            );
                        }
                    )->asArray()
                    )->asArray();
                    return EqualityOf::new(LengthOf::new($selectedPumps), 0)->asBool()
                        ? ['info' => __('flash.selections.pumps_not_found')]
                        : [
                            'selected_pumps' => $selectedPumps,
                            'working_point' => [
                                'x' => $request->flow,
                                'y' => $request->head
                            ]
                        ];
                })
            )
        )->act($request);
    }
}


