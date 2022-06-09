<?php

namespace App\Models\Enums;

use App\Traits\EnumHelpers;
use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * Currency.
 */
final class Currency extends Enum
{
    use EnumHelpers;

    #[Description("RUB")]
    const RUB = 1;

    #[Description("EUR")]
    const EUR = 2;

    #[Description("USD")]
    const USD = 3;
}
