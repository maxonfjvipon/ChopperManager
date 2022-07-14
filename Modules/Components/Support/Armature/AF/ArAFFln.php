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
 * Armature fire for {@see CollectorSwitch::Fln}.
 */
final class ArAFFln extends ArrEnvelope
{
    /**
     * Ctor.
     *
     * Затвор по ду входа +
     * Катушка по ДУ входа +
     * обратный клапан фланцевый по ду выхода +
     * катушка по ДУ выхода +
     * затвор по ду выхода
     *
     * Если горизонтальный:
     * + Катушка с Патрубком по Ду Выхода
     *
     * @param Collection|array $armature
     * @param Pump             $pump
     */
    public function __construct(private Collection|array $armature, private Pump $pump)
    {
        parent::__construct(
            new ArrMerged(
                [
                    // Затвор по ду входа
                    $this->armature->where('type.value', ArmatureType::ZF)
                        ->where('dn', $this->pump->dn_suction)
                        ->first(),
                    // Катушка по ДУ входа
                    $this->armature->where('type.value', ArmatureType::KatF)
                        ->where('dn', $this->pump->dn_suction)
                        ->first(),
                    // обратный клапан фланцевый по ду выхода
                    $this->armature->where('type.value', ArmatureType::OKF)
                        ->where('connection_type.value', ConnectionType::Flanged)
                        ->where('dn', $this->pump->dn_pressure)
                        ->first(),
                    // катушка по ДУ выхода
                    $this->armature->where('type.value', ArmatureType::KatF)
                        ->where('dn', $this->pump->dn_pressure)
                        ->first(),
                    // затвор по ду выхода
                    $this->armature->where('type.value', ArmatureType::ZF)
                        ->where('dn', $this->pump->dn_pressure)
                        ->first(),
                ],
                new ArrIf(
                    $this->pump->isHorizontal(),
                    fn () => [
                        // Катушка с патрубком по ДУ выхода
                        $this->armature->where('type.value', ArmatureType::KatP)
                            ->where('dn', $this->pump->dn_pressure)
                            ->first(),
                    ]
                )
            )
        );
    }
}
