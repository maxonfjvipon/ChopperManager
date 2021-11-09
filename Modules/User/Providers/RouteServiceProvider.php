<?php

namespace Modules\User\Providers;

use Modules\Core\Providers\CoreRouteServiceProvider;

class RouteServiceProvider extends CoreRouteServiceProvider
{
    protected function moduleName(): string
    {
        return 'User';
    }
}
