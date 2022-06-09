<?php

namespace Modules\User\Entities;

use BenSampo\Enum\Enum;

final class UserRole extends Enum
{
    const Admin = 1;
    const Client = 2;
}
