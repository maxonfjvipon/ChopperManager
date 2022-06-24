<?php

namespace Modules\User\Entities;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * User role.
 */
final class UserRole extends Enum
{
    #[Description("Админ")]
    const Admin = 1;

    #[Description("Дилер")]
    const Dealer = 2;
}
