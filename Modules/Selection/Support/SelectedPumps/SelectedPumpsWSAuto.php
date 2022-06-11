<?php

namespace Modules\Selection\Support\SelectedPumps;

use App\Support\Rates\RealRates;
use App\Support\Rates\StickyRates;
use Illuminate\Support\Collection;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFromCallback;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrSticky;
use Maxonfjvipon\Elegant_Elephant\Numerable\Rounded;
use Modules\Components\Entities\Chassis;
use Modules\Components\Entities\Collector;
use Modules\Components\Entities\CollectorMaterial;
use Modules\Components\Entities\CollectorType;
use Modules\Components\Entities\ControlSystem;
use Modules\Components\Entities\ControlSystemType;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\StationType;
use Modules\Selection\Http\Requests\RqMakeSelection;
use Modules\Selection\Support\ArrPumpsForSelecting;
use Modules\Selection\Support\ArrCostStructure;
use Modules\Selection\Support\Performance\PpQEnd;
use Modules\Selection\Support\Performance\PpQStart;
use Modules\Selection\Support\Point\IntersectionPoint;
use Modules\Selection\Support\PumpStationPrice;
use Modules\Selection\Support\Regression\EqFromPumpCoefficients;
use Modules\Selection\Support\TxtCostStructure;
use Modules\Selection\Support\TxtPumpStationName;

/**
 * Water Auto selected pumps
 */
final class SelectedPumpsWSAuto implements Arrayable
{
    /**
     * Ctor.
     * @param RqMakeSelection $request
     * @param Arrayable $dnsMaterials
     */
    public function __construct(
        private RqMakeSelection $request,
        private Arrayable       $dnsMaterials
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        $key = 1;
        $rates = new StickyRates(new RealRates());
        return (new ArrMerged(
            ...new ArrMapped(
                new ArrPumpsForSelecting($this->request), // load pumps
                function (Pump $pump) use (&$key, $rates) {
                    $minDn = DN::values()[array_search($pump->dn_suction, DN::values()) + 1]; // min DN for $pump
                    return new ArrMerged(
                        ...new ArrMapped(
                            $this->request->main_pumps_counts, // foreach by every main pumps count
                            function ($mainPumpsCount) use ($pump, $minDn, &$key, $rates) {
                                $qEnd = (new PpQEnd($pump->performance(), $mainPumpsCount))->asNumber();
                                $chassis = Chassis::appropriateFor($pump, $pumpsCount = $mainPumpsCount + $this->request->reserve_pumps_count);
                                return new ArrIf(
                                    $this->request->flow < $qEnd, // if flow < qEnd
                                    new ArrFromCallback(
                                        function () use ($pump, $qEnd, $mainPumpsCount, $minDn, $pumpsCount, $chassis, &$key, $rates) {
                                            $qStart = (new PpQStart($pump->performance(), $mainPumpsCount))->asNumber();
                                            $intersectionPoint = new IntersectionPoint(
                                                new EqFromPumpCoefficients(
                                                    $pump->coefficientsAt($mainPumpsCount)
                                                ),
                                                $this->request->flow,
                                                $this->request->head
                                            );
                                            return new ArrIf( // if pump is appropriate
                                                $this->request->flow >= $qStart
                                                && $intersectionPoint->x() >= $qStart
                                                && $intersectionPoint->x() <= $qEnd
                                                && $intersectionPoint->y() >= $this->request->head + $this->request->head * (($this->request->deviation ?? 0) / 100),
                                                new ArrFromCallback(
                                                    function () use ($minDn, $pump, $pumpsCount, $mainPumpsCount, $chassis, &$key, $rates) {
                                                        return new ArrMerged(
                                                            ...new ArrMapped( // foreach by collectors
                                                                new ArrMapped(
                                                                    $this->dnsMaterials,
                                                                    fn(array $dnMaterial) => Collector::allOrCached()
                                                                        ->where('dn_common', max($minDn, $dnMaterial['dn']))
                                                                        ->whereIn('dn_pipes', [$pump->dn_suction, $pump->dn_pressure])
                                                                        ->where('pipes_count', $pumpsCount)
                                                                        ->where('material.value', CollectorMaterial::getValueByDescription($dnMaterial['material']))
                                                                        ->where('connection_type.value', $pump->dn_suction <= 50
                                                                            ? ConnectionType::Threaded
                                                                            : ConnectionType::Flanged
                                                                        )
                                                                        ->where('type.value', CollectorType::getTypeByStationType($this->request->station_type))
                                                                ),
                                                                function (Collection $_collectors) use ($pump, $pumpsCount, $mainPumpsCount, $chassis, &$key, $rates) {
                                                                    return new ArrMapped(
                                                                        new ArrMapped(
                                                                            $this->request->control_system_type_ids,
                                                                            fn(int $controlSystemTypeId) => ControlSystem::allOrCached()
                                                                                ->where('power', '>=', $pump->power)
                                                                                ->where('pumps_count', $pumpsCount)
                                                                                ->where('type_id', $controlSystemTypeId)
                                                                                ->first()
                                                                        ),
                                                                        function (?ControlSystem $controlSystem) use ($pump, $mainPumpsCount, $_collectors, $pumpsCount, $chassis, &$key, $rates) {
                                                                            return [
                                                                                'key' => $key++,
                                                                                'created_at' => date_format(now(), 'd.m.Y H:i'),
                                                                                'name' => $name = (new TxtPumpStationName(
                                                                                    $controlSystem,
                                                                                    $pumpsCount,
                                                                                    $pump,
                                                                                    $inputCollector = $_collectors->firstWhere('dn_pipes', $pump->dn_suction),
                                                                                ))->asString(),
                                                                                'pump_article' => $pump->article,
                                                                                'cost_price' => (new Rounded(
                                                                                    new PumpStationPrice(
                                                                                        $costStructure = new ArrSticky(
                                                                                            new ArrCostStructure(
                                                                                                $this->request,
                                                                                                $rates,
                                                                                                [
                                                                                                    'pump' => $pump,
                                                                                                    'control_system' => $controlSystem,
                                                                                                    'chassis' => $chassis,
                                                                                                    'collectors' => $_collectors
                                                                                                ],
                                                                                                $pumpsCount
                                                                                            )
                                                                                        )
                                                                                    ),
                                                                                    2
                                                                                ))->asNumber(),
                                                                                'cost_structure' => (new TxtCostStructure($costStructure))->asString(),
                                                                                'power' => $pump->power,
                                                                                'total_power' => $pump->power * $pumpsCount,
                                                                                'control_system_article' => $controlSystem?->article,

                                                                                'control_system_id' => $controlSystem?->id,
                                                                                'input_collector_id' => $inputCollector?->id,
                                                                                'output_collector_id' => $_collectors->firstWhere('dn_pipes', $pump->dn_pressure)?->id,
                                                                                'chassis_id' => $chassis?->id,
                                                                                'pump_id' => $pump->id,

                                                                                'flow' => $this->request->flow,
                                                                                'head' => $this->request->head,
                                                                                'reserve_pumps_count' => $this->request->reserve_pumps_count,
                                                                                'main_pumps_count' => $mainPumpsCount,
                                                                                'bad' => str_contains($name, "?")
                                                                            ];
                                                                        }
                                                                    );
                                                                }
                                                            )
                                                        );
                                                    }
                                                )
                                            );
                                        }
                                    )
                                );
                            }
                        )
                    );
                },
            )
        ))->asArray();
    }
}
