<?php

namespace Modules\Components\Support\Armature\WS;

use Illuminate\Database\Eloquent\Collection;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Components\Entities\ArmatureType;
use Modules\Pump\Entities\CollectorSwitch;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\Pump;

/**
 * Armature water for {@see CollectorSwitch::FlnToTrd}
 */
final class ArWSFlnToTrd implements Arrayable
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
     * обратный клапан резьбовой по ду выхода +
     * ниппель по ду выхода +
     * шаровый кран резьбовой по ду выхода
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
            // ниппель по ду выхода
            $nip = $this->armature->where('type.value', ArmatureType::NR)
                ->where('dn', $this->pump->dn_pressure)
                ->first(),
            // обратный клапан резьбовой по ду выхода
            $this->armature->where('type.value', ArmatureType::OKF)
                ->where('connection_type.value', ConnectionType::Threaded)
                ->where('dn', $this->pump->dn_pressure)
                ->first(),
            // ниппель по ду выхода
            $nip,
            // шаровый кран резьбовой по ду выхода
            $this->armature->where('type.value', ArmatureType::KR)
                ->where('connection_type.value', ConnectionType::Threaded)
                ->where('dn', $this->pump->dn_pressure)
                ->first(),
        ];
    }
}
