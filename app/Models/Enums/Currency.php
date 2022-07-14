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

    #[Description('RUB')]
    public const RUB = 1;

    #[Description('EUR')]
    public const EUR = 2;

    #[Description('USD')]
    public const USD = 3;
}
