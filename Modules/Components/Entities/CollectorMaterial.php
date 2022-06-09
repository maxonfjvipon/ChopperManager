<?php

namespace Modules\Components\Entities;

use App\Traits\EnumHelpers;
use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * Collector material.
 */
final class CollectorMaterial extends Enum
{
    use EnumHelpers;

    #[Description("AISI-304")]
    const AISI = 1;

    #[Description("Сталь-20")]
    const Steel = 2;
}
