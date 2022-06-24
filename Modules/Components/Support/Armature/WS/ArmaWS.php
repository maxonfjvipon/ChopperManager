<?php

namespace Modules\Components\Support\Armature\WS;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFromCallback;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\Components\Entities\Armature;
use Modules\Components\Support\Armature\ArrArmatureCount;
use Modules\Pump\Entities\CollectorSwitch;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\StationType;

/**
 * Armature for {@see StationType::WS}
 */
final class ArmaWS extends ArrEnvelope
{
    /**
     * @param Pump $pump
     * @param int $pumpsCount
     * @throws Exception
     */
    public function __construct(private Pump $pump, private int $pumpsCount)
    {
        parent::__construct(
            new ArrFromCallback(
                function () {
                    $armature = Armature::allOrCached();
                    return new ArrMapped(
                        match ($this->pump->collector_switch->value) {
                            CollectorSwitch::Trd => new ArWSTrd($armature, $this->pump),
                            CollectorSwitch::OvlFln => new ArWSOvlFLn($armature, $this->pump),
                            CollectorSwitch::Fln => new ArWSFLn($armature, $this->pump),
                            CollectorSwitch::FlnToTrd => new ArWSFLnToTrd($armature, $this->pump)
                        },
                        fn(?Armature $_armature) => new ArrArmatureCount($_armature, $this->pumpsCount)
                    );
                }
            )
        );
    }
}
