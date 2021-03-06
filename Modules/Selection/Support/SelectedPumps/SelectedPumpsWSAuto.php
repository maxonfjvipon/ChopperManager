<?php

namespace Modules\Selection\Support\SelectedPumps;

use App\Interfaces\Rates;
use Illuminate\Support\Collection;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFlatten;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Numerable\NumSticky;
use Modules\Components\Entities\Chassis;
use Modules\Components\Entities\ControlSystem;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Http\Requests\RqMakeSelection;
use Modules\Selection\Support\ArrCollectorsForAutoSelection;
use Modules\Selection\Support\ArrControlSystemForSelection;
use Modules\Selection\Support\ArrPumpsForSelecting;
use Modules\Selection\Support\Performance\PpQEnd;
use Modules\Selection\Support\PumpIsGoodToSelect;

/**
 * Water Auto selected pumps.
 */
final class SelectedPumpsWSAuto extends ArrEnvelope
{
    /**
     * Ctor.
     *
     * @param  RqMakeSelection  $request
     * @param  Arrayable  $dnsMaterials
     * @param  Rates  $rates
     * @param  \Illuminate\Database\Eloquent\Collection  $controlSystems
     * @param  Dealer  $dealer
     */
    public function __construct(
        private RqMakeSelection $request,
        private Arrayable $dnsMaterials,
        private Rates $rates,
        private \Illuminate\Database\Eloquent\Collection $controlSystems,
        private Dealer $dealer
    ) {
        $key = 1;
        parent::__construct(
            new ArrFlatten(
                new ArrMapped(
                    new ArrPumpsForSelecting($this->request), // load pumps
                    function (Pump $pump) use (&$key) {
                        $minDn = DN::minDNforPump($pump); // min DN for $pump

                        return new ArrMapped(
                            $this->request->main_pumps_counts,
                            function ($mainPumpsCount) use ($pump, $minDn, &$key) {
                                $qEnd = new NumSticky(new PpQEnd($pump->performance(), $mainPumpsCount));

                                return new ArrIf(
                                    $this->request->flow < $qEnd->asNumber(), // if flow < qEnd
                                    function () use ($pump, $qEnd, $mainPumpsCount, $minDn, &$key) {
                                        return new ArrIf(
                                            new PumpIsGoodToSelect($this->request, $pump, $mainPumpsCount, $qEnd),
                                            function () use ($minDn, $pump, $mainPumpsCount, &$key) {
                                                $chassis = Chassis::appropriateFor($pump, $pumpsCount = $mainPumpsCount + $this->request->reserve_pumps_count);

                                                return new ArrMapped( // foreach by collectors
                                                    new ArrCollectorsForAutoSelection(
                                                        $this->request,
                                                        $this->dnsMaterials,
                                                        $pump,
                                                        $pumpsCount,
                                                        $minDn
                                                    ),
                                                    function (Collection $_collectors) use ($pump, $pumpsCount, $mainPumpsCount, $chassis, &$key) {
                                                        return new ArrMapped(
                                                            new ArrControlSystemForSelection($this->request, $pump, $pumpsCount, false, $this->controlSystems),
                                                            function (?ControlSystem $controlSystem) use ($pump, $mainPumpsCount, $_collectors, $chassis, &$key) {
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
                                                                    ],
                                                                    $this->dealer
                                                                );
                                                            },
                                                            true
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
}
