<?php

namespace Modules\Selection\Support\SelectedPumps;

use App\Interfaces\Rates;
use Exception;
use Illuminate\Support\Collection;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrayableOf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFiltered;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFlatten;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFromCallback;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrSorted;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrSticky;
use Maxonfjvipon\Elegant_Elephant\Logical\StrContains;
use Maxonfjvipon\Elegant_Elephant\Numerable\NumSticky;
use Maxonfjvipon\Elegant_Elephant\Numerable\Rounded;
use Modules\Components\Entities\Chassis;
use Modules\Components\Entities\ControlSystem;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Http\Requests\RqMakeSelection;
use Modules\Selection\Support\ArrCollectorsForAutoSelection;
use Modules\Selection\Support\ArrControlSystemForSelection;
use Modules\Selection\Support\ArrCostStructure;
use Modules\Selection\Support\ArrPumpsForJockeySelecting;
use Modules\Selection\Support\ArrPumpsForSelecting;
use Modules\Selection\Support\Performance\PpQEnd;
use Modules\Selection\Support\Performance\PpQStart;
use Modules\Selection\Support\Point\IntersectionPoint;
use Modules\Selection\Support\PumpIsGoodToSelect;
use Modules\Selection\Support\PumpStationPrice;
use Modules\Selection\Support\Regression\EqFromPumpCoefficients;
use Modules\Selection\Support\TxtCostStructure;
use Modules\Selection\Support\TxtPumpStationName;

/**
 * Auto selected pumps for AF selection.
 */
final class SelectedPumpsAFAuto extends ArrEnvelope
{
    /**
     * Ctor.
     * @param RqMakeSelection $request
     * @param Arrayable $dnsMaterials
     * @param Rates $rates
     * @throws Exception
     */
    public function __construct(
        private RqMakeSelection $request,
        private Arrayable       $dnsMaterials,
        private Rates           $rates,
    )
    {
        parent::__construct(
            new ArrFromCallback(
                function () {
                    $key = 1;
                    $jockeyPump = null;
                    $jockeyChassis = null;
                    if ($isSprinkler = $this->request->jockey_flow && $this->request->jockey_head && $this->request->jockey_series_ids) {
                        $jockeyPump = self::jockeyPump($this->request, $this->rates);
                        $jockeyChassis = Chassis::appropriateFor($jockeyPump, 1);
                    }
                    return ArrayableOf::items(
                        ...new ArrFlatten(
                            new ArrMapped(
                                new ArrPumpsForSelecting($this->request),
                                function (Pump $pump) use (&$key, $isSprinkler, $jockeyPump, $jockeyChassis) {
                                    $minDn = DN::minDNforPump($pump); // min DN for $pump
                                    return new ArrMapped(
                                        $this->request->main_pumps_counts,
                                        function ($mainPumpsCount) use ($pump, $minDn, &$key, $isSprinkler, $jockeyChassis, $jockeyPump) {
                                            $qEnd = new NumSticky(new PpQEnd($pump->performance(), $mainPumpsCount));
                                            $chassis = Chassis::appropriateFor($pump, $pumpsCount = $mainPumpsCount + $this->request->reserve_pumps_count);
                                            return new ArrIf(
                                                $this->request->flow < $qEnd->asNumber(), // if flow < qEnd
                                                function () use ($pump, $qEnd, $mainPumpsCount, $minDn, $pumpsCount, $chassis, &$key, $jockeyChassis, $jockeyPump, $isSprinkler) {
                                                    return new ArrIf( // if pump is appropriate
                                                        new PumpIsGoodToSelect($this->request, $pump, $mainPumpsCount, $qEnd),
                                                        function () use ($minDn, $pump, $pumpsCount, $mainPumpsCount, $chassis, &$key, $jockeyPump, $jockeyChassis, $isSprinkler) {
                                                            return new ArrMapped( // foreach by collectors
                                                                new ArrCollectorsForAutoSelection(
                                                                    $this->request,
                                                                    $this->dnsMaterials,
                                                                    $pump,
                                                                    $pumpsCount,
                                                                    $minDn
                                                                ),
                                                                function (Collection $_collectors) use ($pump, $pumpsCount, $mainPumpsCount, $chassis, &$key, $isSprinkler, $jockeyChassis, $jockeyPump) {
                                                                    return new ArrMapped(
                                                                        new ArrControlSystemForSelection($this->request, $pump, $pumpsCount, $isSprinkler),
                                                                        function (?ControlSystem $controlSystem) use ($pump, $mainPumpsCount, $_collectors, $pumpsCount, $chassis, &$key, $jockeyChassis, $jockeyPump) {
                                                                            return new ArrSelectedPump(
                                                                                $key,
                                                                                $this->request,
                                                                                $mainPumpsCount,
                                                                                $this->rates,
                                                                                [
                                                                                    'pump' => $pump,
                                                                                    'control_system' => $controlSystem,
                                                                                    'chassis' => $chassis,
                                                                                    'collectors' => $_collectors,
                                                                                    'jockey_pump' => $jockeyPump,
                                                                                    'jockey_chassis' => $jockeyChassis
                                                                                ]
                                                                            );
                                                                        }
                                                                    );
                                                                }
                                                            );
                                                        }
                                                    );
                                                }
                                            );
                                        }
                                    );
                                },
                            ),
                            3
                        )
                    );
                }
            )
        );
    }

    /**
     * Load jockey pump.
     * @param RqMakeSelection $request
     * @param Rates $rates
     * @return Pump
     * @throws Exception
     */
    private static function jockeyPump(RqMakeSelection $request, Rates $rates): Pump
    {
        return ArrSorted::new(
            new ArrMapped(
                new ArrFiltered(
                    new ArrPumpsForJockeySelecting($request->jockey_series_ids),
                    function (Pump $pump) use ($request) {
                        $qEnd = (new PpQEnd($pump->performance(), 1))->asNumber();
                        if ($request->jockey_flow < $qEnd) {
                            $qStart = (new PpQStart($pump->performance(), 1))->asNumber();
                            $intersectionPoint = new IntersectionPoint(
                                new EqFromPumpCoefficients(
                                    $pump->coefficientsAt(1)
                                ),
                                $request->jockey_flow,
                                $request->jockey_head
                            );
                            return $request->jockey_flow >= $qStart
                                && $intersectionPoint->x() >= $qStart + ($qEnd - $qStart) * 0.2
                                && $intersectionPoint->x() <= $qEnd - ($qEnd - $qStart) * 0.2
                                && $intersectionPoint->y() >= $request->jockey_head;

                        }
                        return false;
                    }
                ),
                fn(Pump $pump) => [
                    'pump' => $pump,
                    'cost' => $pump->priceByRates($rates)
                ]
            ),
            'cost'
        )->asArray()[0]['pump'];
    }
}
