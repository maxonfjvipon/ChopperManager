<?php

namespace Modules\ProjectParticipant\Providers;

use App\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use JetBrains\PhpStorm\Pure;

final class RouteServiceProvider extends BaseRouteServiceProvider
{
    #[Pure]
 public function __construct($app)
 {
     parent::__construct($app, 'ProjectParticipant');
 }
}
