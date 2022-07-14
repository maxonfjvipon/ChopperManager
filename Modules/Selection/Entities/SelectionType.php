<?php

namespace Modules\Selection\Entities;

use App\Traits\EnumHelpers;
use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * Selection type.
 */
final class SelectionType extends Enum
{
    use EnumHelpers;

    #[Description('Автоматический')]
    public const Auto = 1;

    #[Description('Ручной')]
    public const Handle = 2;
}
