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

    #[Description("Водоснабжение")]
    const Water = 1;

    #[Description("Пожаротушение")]
    const Fire = 2;

    #[Description("ХВС+ВПВ")]
    const Combine = 3;
}
