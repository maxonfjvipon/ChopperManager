<?php

namespace Modules\Components\Support\Armature\Water;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Components\Entities\Armature;
use Modules\Pump\Entities\CollectorSwitch;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\StationType;

/**
 * Armature for {@see StationType::Water}
 */
final class ArmaWater implements Arrayable
{
    /**
     * @param Pump $pump
     */
    public function __construct(private Pump $pump)
    {
    }


    public function asArray(): array
    {
        $armature = Armature::allOrCached();
        return (match ($this->pump->collector_switch->value) {
            CollectorSwitch::Trd => new ArWtTrd($armature, $this->pump),
            CollectorSwitch::OvlFln => new ArWtOvlFln($armature, $this->pump),
            CollectorSwitch::Fln => new ArWtFln($armature, $this->pump),
            CollectorSwitch::FlnToTrd => new ArWtFlnToTrd($armature, $this->pump)
        })->asArray();
    }
}
