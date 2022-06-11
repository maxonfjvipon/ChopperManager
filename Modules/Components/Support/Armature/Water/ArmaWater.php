<?php

namespace Modules\Components\Support\Armature\Water;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\Components\Entities\Armature;
use Modules\Components\Support\Armature\ArrArmatureCount;
use Modules\Pump\Entities\CollectorSwitch;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\StationType;

/**
 * Armature for {@see StationType::WS}
 */
final class ArmaWater implements Arrayable
{
    /**
     * @param Pump $pump
     * @param int $pumpsCount
     */
    public function __construct(private Pump $pump, private int $pumpsCount)
    {
    }

    /**
     * Returns array of {@see ArrArmatureCount} objects
     * @return array
     * @throws Exception
     */
    public function asArray(): array
    {
        $armature = Armature::allOrCached();
        return [...new ArrMapped(
            match ($this->pump->collector_switch->value) {
                CollectorSwitch::Trd => new ArWtTrd($armature, $this->pump),
                CollectorSwitch::OvlFln => new ArWtOvlFln($armature, $this->pump),
                CollectorSwitch::Fln => new ArWtFln($armature, $this->pump),
                CollectorSwitch::FlnToTrd => new ArWtFlnToTrd($armature, $this->pump)
            },
            fn(?Armature $_armature) => new ArrArmatureCount($_armature, $this->pumpsCount)
        )];
    }
}
