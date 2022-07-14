<?php

namespace Modules\Components\Support\Armature\AF;

use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFromCallback;
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
 * Armature for {@see StationType::AF}.
 */
final class ArmaAF extends ArrEnvelope
{
    /**
     * @param Pump           $pump
     * @param int            $pumpsCount
     * @param Collector|null $inputCollector
     */
    public function __construct(private Pump $pump, private int $pumpsCount, private ?Collector $inputCollector)
    {
        parent::__construct(
            new ArrFromCallback(
                function () {
                    $armature = Armature::allOrCached();

                    return new ArrMerged(
                        new ArrMapped(
                            match ($this->pump->collector_switch->value) {
                                CollectorSwitch::Trd => new ArAFTrd($armature, $this->pump),
                                CollectorSwitch::OvlFln => new ArAFOvlFln($armature, $this->pump),
                                CollectorSwitch::Fln => new ArAFFln($armature, $this->pump),
                                CollectorSwitch::FlnToTrd => new ArAFFlnToTrd($armature, $this->pump)
                            },
                            fn (?Armature $_armature) => new ArrArmatureCount($_armature, $this->pumpsCount)
                        ),
                        [
                            new ArrArmatureCount(
                                $armature->where('type.value', ArmatureType::ZF)
                                    ->where('dn', $this->inputCollector?->dn_common)
                                    ->first(),
                                2 * ($this->pumpsCount - 1)
                            ),
                        ],
                    );
                }
            )
        );
    }
}
