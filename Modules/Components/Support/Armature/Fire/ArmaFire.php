<?php

namespace Modules\Components\Support\Armature\Fire;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Components\Entities\Armature;
use Modules\Pump\Entities\CollectorSwitch;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\StationType;

/**
 * Armature for {@see StationType::Fire}
 */
final class ArmaFire implements Arrayable
{
    /**
     * @param Pump $pump
     */
    public function __construct(private Pump $pump)
    {
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        $armature = Armature::allOrCached();
        return (match ($this->pump->collector_switch->value) {
            CollectorSwitch::Trd => new ArFrTrd($armature, $this->pump),
            CollectorSwitch::OvlFln => new ArFrOvlFln($armature, $this->pump),
            CollectorSwitch::Fln => new ArFrFln($armature, $this->pump),
            CollectorSwitch::FlnToTrd => new ArFrFlnToTrd($armature, $this->pump)
        })->asArray();
    }
}
