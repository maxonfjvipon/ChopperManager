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

    #[Description('Навесной')]
    public const Mounted = 1;

    #[Description('Отдельностоящий')]
    public const Detached = 2;
}
