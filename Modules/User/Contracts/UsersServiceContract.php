<?php

namespace Modules\User\Contracts;

use App\Services\ResourceWithRoutes\ResourceWithCreateRouteInterface;
use App\Services\ResourceWithRoutes\ResourceWithEditRouteInterface;
use App\Services\ResourceWithRoutes\ResourceWithIndexRouteInterface;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;

interface UsersServiceContract extends
    ResourceWithCreateRouteInterface,
    ResourceWithEditRouteInterface,
    ResourceWithIndexRouteInterface
{
    public function index();

    public function edit(int $user_id);

    public function create();

    public function store(CreateUserRequest $request);

    public function update(UpdateUserRequest $request, int $user_id);
}
