<?php

namespace Modules\Components\Support\Armature\AF;

use Illuminate\Database\Eloquent\Collection;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Components\Entities\ArmatureType;
use Modules\Pump\Entities\CollectorSwitch;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\Pump;

/**
 * Armature fire for {@see CollectorSwitch::OvlFln}.
 */
final class ArAFOvlFln extends ArrEnvelope
{
    /**
     * Ctor.
     *
     * Кран резьбовой по ду входа +
     * ниппель по ду выхода +
     * обратный клапан резьбовой по ду выхода +
     * Кран резьбовой по ду выхода
     *
     * Если горизонтальный:
     * + ниппель по ду выхода +
     * комплект резьбового тройника по Ду выхода
     *
     * @param Collection|array $armature
     * @param Pump             $pump
     */
    public function __construct(private Collection|array $armature, private Pump $pump)
    {
        parent::__construct(
            new ArrMerged(
                [
                    // кран резьбовой по ду входа
                    $this->armature->where('type.value', ArmatureType::KR)
                        ->where('connection_type.value', ConnectionType::Threaded)
                        ->where('dn', $this->pump->dn_suction)
                        ->first(),
                    // ниппель по ду выхода
                    $this->armature->where('type.value', ArmatureType::NR)
                        ->where('dn', $this->pump->dn_pressure)
                        ->first(),
                    // обратный клапан резьбовой по ду выхода
                    $this->armature->where('type.value', ArmatureType::OKF)
                        ->where('connection_type.value', ConnectionType::Threaded)
                        ->where('dn', $this->pump->dn_pressure)
                        ->first(),
                    // кран резьбовой по ду выхода
                    $this->armature->where('type.value', ArmatureType::KR)
                        ->where('connection_type.value', ConnectionType::Threaded)
                        ->where('dn', $this->pump->dn_pressure)
                        ->first(),
                ],
                new ArrIf(
                    $this->pump->isHorizontal(),
                    new ArAFNipKRT($this->armature, $this->pump)
                )
            )
        );
    }
}
