<?php

namespace Modules\Components\Support\Armature\Fire;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Components\Entities\ArmatureType;
use Modules\Pump\Entities\CollectorSwitch;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\Pump;

/**
 * Armature fire for {@see CollectorSwitch::Trd}
 */
final class ArFrTrd implements Arrayable
{
    /**
     * @param Collection|array $armature
     * @param Pump $pump
     */
    public function __construct(private Collection|array $armature, private Pump $pump)
    {
    }

    /**
     * Кран резьбовой по ду входа +
     * быстросъемное соединение по ду входа +
     * быстросъемное соединение по ду Выхода  +
     * ниппель по Ду Выхода +
     * ниппель по ду выхода +
     * комплект резьбового тройника по Ду выхода +
     * обратный клапан резьбовой по ду выхода +
     * Кран резьбовой по ду выхода
     */
    public function asArray(): array
    {
        return [
            // кран резьбовой по ду входа
            $this->armature->where('type.value', ArmatureType::KR)
                ->where('connection_type.value', ConnectionType::Threaded)
                ->where('dn', $this->pump->dn_suction)
                ->first(),
            // быстросъемное соединение по ду входа
            $this->armature->where('type.value', ArmatureType::AM)
                ->where('dn', $this->pump->dn_suction)
                ->first(),
            // быстросъемное соединение по ду выхода
            $this->armature->where('type.value', ArmatureType::AM)
                ->where('dn', $this->pump->dn_pressure)
                ->first(),
            // ниппель по ду выхода
            $nip = $this->armature->where('type.value', ArmatureType::NR)
                ->where('dn', $this->pump->dn_pressure)
                ->first(),
            // ниппель по ду выхода
            $nip,
            // комплект резьбового тройника по Ду выхода
            $this->armature->where('type.value', ArmatureType::KRT)
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
        ];
    }
}
