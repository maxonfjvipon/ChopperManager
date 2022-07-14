<?php

namespace Modules\Components\Support\Armature\WS;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Components\Entities\ArmatureType;
use Modules\Pump\Entities\CollectorSwitch;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\Pump;

/**
 * Armature water for {@see CollectorSwitch::OvlFln}.
 */
final class ArWSOvlFln implements Arrayable
{
    public function __construct(private Collection|array $armature, private Pump $pump)
    {
    }

    /**
     * Кран резьбовой по ду входа +
     * ниппель по ду выхода +
     * ниппель по ду выхода +
     * обратный клапан резьбовой по ду выхода +
     * Кран резьбовой по ду выхода.
     *
     * @throws Exception
     */
    public function asArray(): array
    {
        return [
            // кран резьбовой по ду входа
            $this->armature->where('type.value', ArmatureType::KR)
                ->where('connection_type.value', ConnectionType::Threaded)
                ->where('dn', $this->pump->dn_suction)
                ->first(),
            // ниппель по ду выхода x2
            $nip = $this->armature->where('type.value', ArmatureType::NR)
                ->where('dn', $this->pump->dn_pressure)
                ->first(),
            $nip,
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
