<?php


namespace Modules\User\Services;


use App\Services\ModuleResourceServiceInterface;
use Illuminate\Http\RedirectResponse;
use Modules\User\Http\Requests\Interfaces\UserCreatable;
use Modules\User\Http\Requests\Interfaces\UserUpdatable;

interface UsersServicesInterface extends ModuleResourceServiceInterface
{
    public function __index();

    public function __create();

    public function __edit(int $id);

    public function __update(UserUpdatable $request, int $user_id): RedirectResponse;

    public function __store(UserCreatable $request): RedirectResponse;
}
