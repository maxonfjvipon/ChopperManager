<?php

namespace Modules\Pump\Entities;

use App\Traits\EnumHelpers;
use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * Collector switch.
 */
final class CollectorSwitch extends Enum
{
    use EnumHelpers;

    #[Description('Резьба')]
    public const Trd = 1;

    #[Description('Овальный фланец')]
    public const OvlFln = 2;

    #[Description('Фланец')]
    public const Fln = 3;

    #[Description('Фланец на резьбу')]
    public const FlnToTrd = 4;
}
