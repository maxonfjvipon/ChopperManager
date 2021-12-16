<?php

namespace App\Services;

use App\Services\ResourceWithRoutes\ResourceWithCreateRouteInterface;
use App\Services\ResourceWithRoutes\ResourceWithEditRouteInterface;
use App\Services\ResourceWithRoutes\ResourceWithIndexRouteInterface;
use App\Services\ResourceWithRoutes\ResourceWithShowRouteInterface;

interface ModuleResourceServiceInterface extends
    ResourceWithIndexRouteInterface,
    ResourceWithShowRouteInterface,
    ResourceWithCreateRouteInterface,
    ResourceWithEditRouteInterface
{
}
