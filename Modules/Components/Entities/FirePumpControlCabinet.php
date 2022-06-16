<?php

namespace Modules\Components\Entities;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class FirePumpControlCabinet extends Enum
{
    #[Description("ШУПН")]
    const NoJockey = 1;

    #[Description("ШУПН с жокеем")]
    const WithJockey = 2;
}
