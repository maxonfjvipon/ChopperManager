<?php

namespace Modules\Components\Providers;

use JetBrains\PhpStorm\Pure;
use App\Providers\RouteServiceProvider as BaseRouteServiceProvider;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    #[Pure] public function __construct($app)
    {
        parent::__construct($app, "Components");
    }
}
