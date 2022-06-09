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

    #[Description("Резьба")]
    const Trd = 1;

    #[Description("Овальный фланец")]
    const OvlFln = 2;

    #[Description("Фланец")]
    const Fln = 3;

    #[Description("Фланец на резьбу")]
    const FlnToTrd = 4;
}
