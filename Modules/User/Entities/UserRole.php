<?php

namespace Modules\User\Entities;

use App\Traits\EnumHelpers;
use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * User role.
 */
final class UserRole extends Enum
{
    use EnumHelpers;

    #[Description('Админ')]
    public const Admin = 1;

    #[Description('Дилер')]
    public const Dealer = 2;
}
