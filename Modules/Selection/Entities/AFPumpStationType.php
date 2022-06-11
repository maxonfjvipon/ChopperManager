<?php

namespace Modules\Selection\Entities;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class AFPumpStationType extends Enum
{
    #[Description("Сприклерная")]
    const Sprinkler = 1;

    #[Description("Дренчерная")]
    const Deluge = 2;
}
