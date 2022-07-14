<?php

namespace Modules\Selection\Entities;

use App\Traits\EnumHelpers;
use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * Station type.
 */
final class StationType extends Enum
{
    use EnumHelpers;

    #[Description('Водоснабжение')]
    public const WS = 1;

    #[Description('Пожаротушение')]
    public const AF = 2;

    #[Description('ХВС+ВПВ')]
    public const Combine = 3;
}
