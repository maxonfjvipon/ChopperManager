<?php

namespace Modules\Pump\Entities;

use App\Traits\EnumHelpers;
use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class PumpOrientation extends Enum
{
    use EnumHelpers;

    #[Description('Вертикальный')]
    public const Vertical = 1;

    #[Description('Горизонтальный')]
    public const Horizontal = 2;
}
