<?php

namespace Modules\PumpManager\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Core\Providers\CoreRouteServiceProvider;

class RouteServiceProvider extends CoreRouteServiceProvider
{
    protected function moduleName(): string
    {
        return 'PumpManager';
    }
}
