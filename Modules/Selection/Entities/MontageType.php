<?php

namespace Modules\Selection\Entities;

use App\Traits\EnumHelpers;
use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * Montage type.
 */
final class MontageType extends Enum
{
    use EnumHelpers;

    #[Description("Навесной")]
    const Mounted = 1;

    #[Description("Отдельностоящий")]
    const Detached = 2;
}
