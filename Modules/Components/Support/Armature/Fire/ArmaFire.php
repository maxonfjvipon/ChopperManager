<?php

namespace Modules\Components\Support\Armature\Fire;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Components\Entities\Armature;
use Modules\Components\Entities\ArmatureType;
use Modules\Components\Entities\Collector;
use Modules\Components\Support\Armature\ArrArmatureCount;
use Modules\Pump\Entities\CollectorSwitch;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\StationType;

/**
 * Armature for {@see StationType::AF}
 */
final class ArmaFire implements Arrayable
{
    /**
     * @param Pump $pump
     * @param int $pumpsCount
     * @param Collector|null $inputCollector
     */
    public function __construct(private Pump $pump, private int $pumpsCount, private ?Collector $inputCollector)
    {
    }

    /**
     * Returns array of {@see ArrArmatureCount} objects
     * @inheritDoc
     */
    public function asArray(): array
    {
        $armature = Armature::allOrCached();
        return [
            ...new ArrMapped(
                match ($this->pump->collector_switch->value) {
                    CollectorSwitch::Trd => new ArFrTrd($armature, $this->pump),
                    CollectorSwitch::OvlFln => new ArFrOvlFln($armature, $this->pump),
                    CollectorSwitch::Fln => new ArFrFln($armature, $this->pump),
                    CollectorSwitch::FlnToTrd => new ArFrFlnToTrd($armature, $this->pump)
                },
                fn(?Armature $_armature) => new ArrArmatureCount($_armature, $this->pumpsCount)
            ),
            new ArrArmatureCount(
                $armature->where('type.value', ArmatureType::ZF)
                    ->where('dn', $this->inputCollector?->dn_common)
                    ->first(),
                2 * ($this->pumpsCount - 1)
            )
        ];
    }
}
