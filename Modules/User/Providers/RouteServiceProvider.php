<?php

namespace Modules\User\Providers;

use Modules\Project\Providers\CoreRouteServiceProvider;

class RouteServiceProvider extends CoreRouteServiceProvider
{
    protected function moduleName(): string
    {
        return 'User';
    }
}
