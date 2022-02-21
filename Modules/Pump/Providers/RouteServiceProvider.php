<?php

namespace Modules\Pump\Providers;

use Modules\Project\Providers\CoreRouteServiceProvider;

class RouteServiceProvider extends CoreRouteServiceProvider
{
    protected function moduleName(): string
    {
        return 'Pump';
    }
}
