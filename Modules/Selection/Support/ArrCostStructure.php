<?php

namespace Modules\Selection\Support;

use App\Interfaces\Rates;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Logical\Conjunction;
use Maxonfjvipon\Elegant_Elephant\Logical\KeyExists;
use Modules\Components\Entities\Armature;
use Modules\Components\Entities\AssemblyJob;
use Modules\Components\Entities\FirePumpControlCabinet;
use Modules\Selection\Entities\StationType;
use Modules\Selection\Http\Requests\RqMakeSelection;

/**
 * Pump station cost components.
 */
final class ArrCostStructure extends ArrEnvelope
{
    /**
     * Ctor.
     *
     * @throws Exception
     */
    public function __construct(
        private RqMakeSelection $request,
        private Rates $rates,
        private array $components,
        private int $pumpsCount,
    ) {
        parent::__construct(
            new ArrMerged(
                [
                    'pump' => $pumpPrice = ($pump = $this->components['pump'])->priceByRates($this->rates),
                    'pumps' => $pumpPrice * $this->pumpsCount,
                    'control_system' => ($controlSystem = $this->components['control_system'])?->priceByRates($this->rates),
                    'chassis' => $this->components['chassis']?->priceByRates($this->rates),
                    'input_collector' => ($inputCollector = $this->components['collectors']
                        ->firstWhere('dn_pipes', $pump->dn_suction))
                        ?->priceByRates($this->rates),
                    'output_collector' => $this->components['collectors']
                        ->firstWhere('dn_pipes', $pump->dn_pressure)
                        ?->priceByRates($this->rates),
                    'armature' => Armature::price(
                        $pump,
                        StationType::getValue($this->request->station_type),
                        $this->pumpsCount,
                        $this->rates,
                        $inputCollector
                    ),
                    'job' => AssemblyJob::allOrCached()
                        ->where('pumps_count', $this->pumpsCount)
                        ->where('pumps_weight', '>=', $pump->weight)
                        ->where('control_system_type_id',
                            match ($this->request->station_type) {
                                StationType::getKey(StationType::WS) => $controlSystem?->type_id,
                                StationType::getKey(StationType::AF) => $controlSystem
                                    ? $controlSystem->has_jockey->value
                                        ? FirePumpControlCabinet::WithJockey
                                        : FirePumpControlCabinet::NoJockey
                                    : null
                            }
                        )
                        ->sortBy('pumps_weight')
                        ->first()
                        ?->priceByRates($this->rates),
                ],
                new ArrIf(
                    new Conjunction(
                        new KeyExists('jockey_pump', $this->components),
                        new KeyExists('jockey_chassis', $this->components),
                    ),
                    fn () => new ArrIf(
                        (bool) $this->components['jockey_pump'],
                        fn () => [
                            'jockey_pump' => $this->components['jockey_pump']->priceByRates($this->rates),
                            'jockey_chassis' => $this->components['jockey_chassis']?->priceByRates($this->rates),
                        ]
                    )
                )
            )
        );
    }
}
