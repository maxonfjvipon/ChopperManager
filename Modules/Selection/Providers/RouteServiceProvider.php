<?php

namespace Modules\Selection\Providers;

use Modules\Project\Providers\CoreRouteServiceProvider;

class RouteServiceProvider extends CoreRouteServiceProvider
{
    protected function moduleName(): string
    {
        return 'Selection';
    }
}
