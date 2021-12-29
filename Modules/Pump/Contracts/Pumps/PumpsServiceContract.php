<?php

namespace Modules\Pump\Contracts\Pumps;

use App\Services\ResourceWithRoutes\ResourceWithIndexRouteInterface;
use Illuminate\Http\JsonResponse;
use Inertia\Response;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Http\Requests\LoadPumpsRequest;
use Modules\Pump\Http\Requests\PumpShowRequest;

interface PumpsServiceContract extends
    ResourceWithIndexRouteInterface
{
    public function index(): Response;

    public function show(PumpShowRequest $request, Pump $pump);

    public function load(LoadPumpsRequest $request): JsonResponse;
}
