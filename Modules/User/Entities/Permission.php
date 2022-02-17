<?php

namespace Modules\User\Entities;

use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class Permission extends \Spatie\Permission\Models\Permission
{
    use UsesTenantConnection;
}
