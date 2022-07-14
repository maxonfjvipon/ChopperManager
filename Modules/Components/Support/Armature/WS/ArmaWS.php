<?php

namespace Modules\Components\Support\Armature\WS;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFromCallback;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\Components\Entities\Armature;
use Modules\Components\Support\Armature\ArrArmatureCount;
use Modules\Pump\Entities\CollectorSwitch;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\StationType;

/**
 * Armature for {@see StationType::WS}.
 */
final class ArmaWS extends ArrEnvelope
{
    /**
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
                            CollectorSwitch::OvlFln => new ArWSOvlFln($armature, $this->pump),
                            CollectorSwitch::Fln => new ArWSFln($armature, $this->pump),
                            CollectorSwitch::FlnToTrd => new ArWSFlnToTrd($armature, $this->pump)
                        },
                        fn (?Armature $_armature) => new ArrArmatureCount($_armature, $this->pumpsCount)
                    );
                }
            )
        );
    }
}
