<?php

namespace Modules\Selection\Entities;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class AFPumpStationType extends Enum
{
    #[Description('Сприклерная')]
    public const Sprinkler = 1;

    #[Description('Дренчерная')]
    public const Deluge = 2;
}
