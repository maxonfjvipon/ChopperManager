<?php

namespace Modules\User\Entities;

use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Role extends \Spatie\Permission\Models\Role
{
    use UsesTenantConnection;
}
