<?php

namespace Modules\Pump\Providers;

use Modules\Core\Providers\CoreRouteServiceProvider;

class RouteServiceProvider extends CoreRouteServiceProvider
{
    protected function moduleName(): string
    {
        return 'Pump';
    }
}
