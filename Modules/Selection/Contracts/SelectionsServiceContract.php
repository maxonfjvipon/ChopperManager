<?php


namespace Modules\Selection\Contracts;

use App\Services\ResourceWithRoutes\ResourceWithCreateRouteInterface;
use App\Services\ResourceWithRoutes\ResourceWithIndexRouteInterface;
use App\Services\ResourceWithRoutes\ResourceWithShowRouteInterface;
use Inertia\Response;
use Modules\Selection\Entities\Selection;

interface SelectionsServiceContract extends
    ResourceWithIndexRouteInterface,
    ResourceWithCreateRouteInterface,
    ResourceWithShowRouteInterface
{
    public function index($project_id);

    public function show(Selection $selection): Response;

    public function create($project_id);
}
