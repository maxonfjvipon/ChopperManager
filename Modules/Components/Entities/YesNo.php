<?php

namespace Modules\Components\Entities;

use App\Traits\EnumHelpers;
use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class YesNo extends Enum
{
    use EnumHelpers;

    #[Description("Да")]
    const Yes = true;

    #[Description("Нет")]
    const No = false;
}
