<?php

namespace Modules\User\Entities;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * Client role.
 */
final class ClientRole extends Enum
{
    #[Description("Проектный институт")]
    const DesignInstitute = 1;

    #[Description("Диллер")]
    const Dealer = 2;
}
