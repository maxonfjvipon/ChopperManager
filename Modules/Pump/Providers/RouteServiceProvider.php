<?php

namespace Modules\Pump\Providers;

use App\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use JetBrains\PhpStorm\Pure;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    #[Pure]
 public function __construct($app)
 {
     parent::__construct($app, 'Pump');
 }
}
