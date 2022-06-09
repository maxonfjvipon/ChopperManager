<?php

namespace Modules\Selection\Support;

use App\Support\Rates\Rates;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Components\Entities\Armature;
use Modules\Components\Entities\AssemblyJob;
use Modules\Selection\Entities\StationType;
use Modules\Selection\Http\Requests\RqMakeSelection;

/**
 * Pump station cost components.
 */
final class ArrCostStructure implements Arrayable
{
    /**
     * Ctor.
     * @param RqMakeSelection $request
     * @param Rates $rates
     * @param array $components
     * @param int $pumpsCount
     */
    public function __construct(
        private RqMakeSelection $request,
        private Rates $rates,
        private array           $components,
        private int             $pumpsCount
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        $pump = $this->components['pump'];
        return [
            'pump' => $pump->priceByRates($this->rates),
            'control_system' => ($controlSystem = $this->components['control_system'])?->priceByRates($this->rates),
            'chassis' => $this->components['chassis']?->priceByRates($this->rates),
            'input_collector' => $this->components['collectors']->firstWhere('dn_pipes', $pump->dn_suction)?->priceByRates($this->rates),
            'output_collector' => $this->components['collectors']->firstWhere('dn_pipes', $pump->dn_pressure)?->priceByRates($this->rates),
            'armature' => Armature::price($pump, StationType::getValue($this->request->station_type), $this->pumpsCount, $this->rates),
            'job' => AssemblyJob::allOrCached()
                ->where('pumps_count', $this->pumpsCount)
                ->where('pumps_weight', '>=', $pump->weight)
                ->where('control_system_type_id', $controlSystem?->type_id)
                ->sortBy('pumps_weight')
                ->first()
                ?->priceByRates($this->rates)
        ];
    }
}
