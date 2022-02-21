<?php

namespace Modules\Project\Providers;

class RouteServiceProvider extends CoreRouteServiceProvider
{
    protected function moduleName(): string
    {
        return 'Project';
    }
}
