<?php

namespace Modules\Selection\Support\SelectedPumps;

use App\Support\Rates\RealRates;
use App\Support\Rates\StickyRates;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrSticky;
use Maxonfjvipon\Elegant_Elephant\Numerable\Rounded;
use Modules\Components\Entities\Chassis;
use Modules\Components\Entities\Collector;
use Modules\Components\Entities\CollectorMaterial;
use Modules\Components\Entities\CollectorType;
use Modules\Components\Entities\ControlSystem;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Http\Requests\RqMakeSelection;
use Modules\Selection\Support\ArrCostStructure;
use Modules\Selection\Support\PumpStationPrice;
use Modules\Selection\Support\TxtCostStructure;
use Modules\Selection\Support\TxtPumpStationName;

final class SelectedPumpsAFHandle implements Arrayable
{
    /**
     * Ctor.
     * @param RqMakeSelection $request
     */
    public function __construct(
        private RqMakeSelection $request,
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
        $pump = Pump::find($this->request->pump_id)->load([
            'series',
            'series.brand',
            'coefficients' => function ($query) {
                $query->whereBetween(
                    'position',
                    [1, $this->request->main_pumps_count + $this->request->reserve_pumps_count]
                );
            }]);
        $dnMaterial = [
            'dn' => ($explodedDnMaterial = explode(" ", $this->request->collector))[0],
            'material' => $explodedDnMaterial[1]
        ];
        $chassis = Chassis::appropriateFor($pump, $pumpsCount = $this->request->main_pumps_count + $this->request->reserve_pumps_count);
        $collectors = Collector::allOrCached()
            ->where('dn_common', max(DN::values()[array_search($pump->dn_suction, DN::values()) + 1], $dnMaterial['dn']))
            ->whereIn('dn_pipes', [$pump->dn_suction, $pump->dn_pressure])
            ->where('pipes_count', $pumpsCount)
            ->where('material.value', CollectorMaterial::getValueByDescription($dnMaterial['material']))
            ->where('connection_type.value', $pump->dn_suction <= 50
                ? ConnectionType::Threaded
                : ConnectionType::Flanged
            )
            ->where('type.value', CollectorType::getTypeByStationType($this->request->station_type));
        $jockeyPump = null;
        $jockeyChassis = null;
        if ($isSprinkler = $this->request->jockey_flow && $this->request->jockey_head && $this->request->jockey_pump_id) {
            $jockeyPump = Pump::find($this->request->jockey_pump_id);
            $jockeyChassis = Chassis::appropriateFor($jockeyPump, 1);
        }
        return ArrMapped::new(
            new ArrMapped(
                $this->request->control_system_type_ids,
                fn(int $controlSystemTypeId) => ControlSystem::allOrCached()
                    ->where('power', '>=', $pump->power)
                    ->where('pumps_count', $pumpsCount)
                    ->where('type_id', $controlSystemTypeId)
                    ->where('avr.value', $this->request->boolean('avr'))
                    ->where('gate_valves_count', $this->request->gate_valves_count)
                    ->where('kkv.value', $this->request->boolean('kkv'))
                    ->where('on_street.value', $this->request->boolean('on_street'))
                    ->where('has_jockey.value', $isSprinkler)
                    ->sortBy('power')
                    ->first()
            ),
            function (?ControlSystem $controlSystem) use ($pump, $collectors, $pumpsCount, $chassis, &$key, $rates, $jockeyPump, $jockeyChassis) {
                return [
                    'key' => $key++,
                    'created_at' => date_format(now(), 'd.m.Y H:i'),
                    'name' => $name = (new TxtPumpStationName(
                        $controlSystem,
                        $pumpsCount,
                        $pump,
                        $inputCollector = $collectors->firstWhere('dn_pipes', $pump->dn_suction),
                        $jockeyPump
                    ))->asString(),
                    'pump_article' => $pump->article,
                    'cost_price' => Rounded::new(
                        new PumpStationPrice(
                            $costStructure = new ArrSticky(
                                new ArrCostStructure(
                                    $this->request,
                                    $rates,
                                    [
                                        'pump' => $pump,
                                        'control_system' => $controlSystem,
                                        'chassis' => $chassis,
                                        'collectors' => $collectors,
                                        'jockey_pump' => $jockeyPump,
                                        'jockey_chassis' => $jockeyChassis,
                                    ],
                                    $pumpsCount
                                )
                            )
                        ),
                        2
                    )->asNumber(),
                    'cost_structure' => (new TxtCostStructure($costStructure))->asString(),
                    'power' => $pump->power,
                    'total_power' => $pump->power * $pumpsCount,
                    'control_system_article' => $controlSystem?->article,

                    'control_system_id' => $controlSystem?->id,
                    'input_collector_id' => $inputCollector?->id,
                    'output_collector_id' => $collectors->firstWhere('dn_pipes', $pump->dn_pressure)?->id,
                    'chassis_id' => $chassis?->id,
                    'pump_id' => $pump->id,

                    'jockey_pump_id' => $jockeyPump?->id,
                    'jockey_chassis_id' => $jockeyChassis?->id,
                    'jockey_flow' => $this->request->jockey_flow,
                    'jockey_head' => $this->request->jockey_head,

                    'flow' => $this->request->flow,
                    'head' => $this->request->head,
                    'reserve_pumps_count' => $this->request->reserve_pumps_count,
                    'main_pumps_count' => $this->request->main_pumps_count,
                    'bad' => str_contains($name, "?")
                ];
            }
        )->asArray();
    }
}