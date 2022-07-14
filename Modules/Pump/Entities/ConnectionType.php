<?php

namespace Modules\Pump\Entities;

use App\Traits\EnumHelpers;
use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * Connection type.
 */
final class ConnectionType extends Enum
{
    use EnumHelpers;

    #[Description('Резьбовой')]
    public const Threaded = 1;

    #[Description('Фланцевый')]
    public const Flanged = 2;
}
