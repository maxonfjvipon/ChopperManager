<?php

namespace Modules\Selection\Support\SelectedPumps;

use App\Support\Rates\RealRates;
use App\Support\Rates\StickyRates;
use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFiltered;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Text\TxtImploded;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\SelectionRange;
use Modules\Selection\Http\Requests\MakeSelectionRequest;
use Modules\Selection\Support\ArrPumpsForSelecting;
use Modules\Selection\Support\Performance\PpQEnd;
use Modules\Selection\Support\Performance\PpQStart;
use Modules\Selection\Support\Point\IntersectionPoint;
use Modules\Selection\Support\Regression\EqFromPumpCoefficients;

final class SelectedSinglePumps implements Arrayable
{
    /**
     * @var Request $request
     */
    private Request $request;

    /**
     * @param MakeSelectionRequest $req
     */
    public function __construct(MakeSelectionRequest $req)
    {
        $this->request = $req;
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        $num = 1;
        $range = SelectionRange::allOrCached()->find($this->request->range_id);
        $rates = new StickyRates(new RealRates());
        return  (new ArrMerged(
            ...(new ArrMapped(
                new ArrPumpsForSelecting($this->request),
                function (Pump $pump) use (&$num, $range, $rates) {
                    return new ArrFiltered(
                        new ArrMapped(
                            $this->request->main_pumps_counts,
                            function ($mainPumpsCount) use (&$num, $pump, $rates, $range) {
                                $qEnd = (new PpQEnd($pump->performance(), $mainPumpsCount))->asNumber();
                                if ($this->request->flow < $qEnd) {
                                    $qStart = (new PpQStart($pump->performance(), $mainPumpsCount))->asNumber();
                                    $intersectionPoint = new IntersectionPoint(
                                        new EqFromPumpCoefficients(
                                            $pump->coefficientsAt($mainPumpsCount)
                                        ),
                                        $this->request->flow,
                                        $this->request->head
                                    );
                                    if ($this->request->range_id === SelectionRange::$CUSTOM) {
                                        $rStart = $this->request->custom_range[0] / 100;
                                        $rEnd = (100 - $this->request->custom_range[1]) / 100;
                                    } else {
                                        $rStart = $range->value;
                                        $rEnd = $range->value;
                                    }
                                    if ($this->request->flow >= $qStart
                                        && $intersectionPoint->x() >= $qStart + ($qEnd - $qStart) * $rStart
                                        && $intersectionPoint->x() <= $qEnd - ($qEnd - $qStart) * $rEnd
                                        && $intersectionPoint->y() >= $this->request->head + $this->request->head
                                        * (($this->request->deviation ?? 0) / 100)
                                    ) {
                                        $pumpPrices = $pump->currentPrices($rates);
                                        return [
                                            'key' => $num++,
                                            'pumps_count' => $pumpsCount = $mainPumpsCount + $this->request->reserve_pumps_count,
                                            'name' => (new TxtImploded(
                                                " ",
                                                $pumpsCount,
                                                $pump->brand->name,
                                                $pump->name
                                            ))->asString(),
                                            'pump_id' => $pump->id,
                                            'article_num' => $pump->article_num_main,
                                            'retail_price' => round($pumpPrices['simple'], 2),
                                            'discounted_price' => round($pumpPrices['discounted'], 2),
                                            'retail_price_total' => round($pumpPrices['simple'] * $pumpsCount, 2),
                                            'discounted_price_total' => round($pumpPrices['discounted'] * $pumpsCount, 2),
                                            'dn_suction' => $pump->dn_suction->value,
                                            'dn_pressure' => $pump->dn_pressure->value,
                                            'rated_power' => $pump->rated_power,
                                            'power_total' => round($pump->rated_power * $pumpsCount, 4),
                                            'ptp_length' => $pump->ptp_length,
                                            'head' => $this->request->head,
                                            'flow' => $this->request->flow,
                                            'main_pumps_count' => $mainPumpsCount,
                                            'fluid_temperature' => $this->request->fluid_temperature,
                                        ];
                                    }
                                }
                            }
                        ),
                        fn($obj) => !!$obj
                    );
                }
            ))->asArray()
        ))->asArray();
    }
}
