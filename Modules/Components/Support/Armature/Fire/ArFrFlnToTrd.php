<?php

namespace Modules\Components\Support\Armature\Fire;

use Illuminate\Database\Eloquent\Collection;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Components\Entities\ArmatureType;
use Modules\Pump\Entities\CollectorSwitch;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\Pump;

/**
 * Armature fire for {@see CollectorSwitch::FlnToTrd}
 */
final class ArFrFlnToTrd implements Arrayable
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
     * фланец с переходом на резьбу по ду входа +
     * фланец с переходом на резьбу по ду выхода +
     * ниппель по ду выхода +
     * ниппель по ду выхода  +
     * комплект резьбового тройника по Ду выхода +
     * обратный клапан резьбовой по ду выхода +
     * кран резьбовой по ду выхода
     */
    public function asArray(): array
    {
        return [
            // кран резьбовой по ду входа
            $this->armature->where('type.value', ArmatureType::KR)
                ->where('connection_type.value', ConnectionType::Threaded)
                ->where('dn', $this->pump->dn_suction)
                ->first(),
            // фланец с переходом на резьбу по ду входа
            $this->armature->where('type.value', ArmatureType::FNR)
                ->where('dn', $this->pump->dn_suction)
                ->first(),
            // фланец с переходом на резьбу по ду выхода
            $this->armature->where('type.value', ArmatureType::FNR)
                ->where('dn', $this->pump->dn_pressure)
                ->first(),
            // ниппель по ду выхода x2
            $nip = $this->armature->where('type.value', ArmatureType::NR)
                ->where('dn', $this->pump->dn_pressure)
                ->first(),
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
