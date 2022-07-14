<?php

namespace Modules\Components\Support\Armature\AF;

use Illuminate\Database\Eloquent\Collection;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Components\Entities\ArmatureType;
use Modules\Pump\Entities\Pump;

/**
 * Armature with {@see ArmatureType::NP} and {@see ArmatureType::KRT}.
 */
final class ArAFNipKRT implements Arrayable
{
    /**
     * Ctor.
     */
    public function __construct(private Collection|array $armature, private Pump $pump)
    {
    }

    public function asArray(): array
    {
        return [
            $this->armature->where('type.value', ArmatureType::NR)
                ->where('dn', $this->pump->dn_pressure)
                ->first(),
            $this->armature->where('type.value', ArmatureType::KRT)
                ->where('dn', $this->pump->dn_pressure)
                ->first(),
        ];
    }
}
