<?php

namespace Modules\User\Providers;

use App\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use JetBrains\PhpStorm\Pure;

/**
 * User module route service provider.
 */
class RouteServiceProvider extends BaseRouteServiceProvider
{
    #[Pure] public function __construct($app)
    {
        parent::__construct($app, "User");
    }
}
