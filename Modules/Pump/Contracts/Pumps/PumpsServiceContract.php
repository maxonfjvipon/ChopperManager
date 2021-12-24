<?php

namespace Modules\Pump\Contracts\Pumps;

use App\Services\ResourceWithRoutes\ResourceWithIndexRouteInterface;
use App\Services\ResourceWithRoutes\ResourceWithShowRouteInterface;
use Illuminate\Http\JsonResponse;
use Inertia\Response;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Http\Requests\PumpShowRequest;

interface PumpsServiceContract extends
    ResourceWithShowRouteInterface,
    ResourceWithIndexRouteInterface
{
    public function index(): Response;

    public function show(PumpShowRequest $request, Pump $pump);

    public function load(): JsonResponse;
}
