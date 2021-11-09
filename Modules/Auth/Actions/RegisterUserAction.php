<?php

namespace Modules\Auth\Actions;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Support\Executable;
use Modules\User\Traits\UsesUserModel;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

abstract class RegisterUserAction implements Executable
{
    use UsesTenantModel, UsesUserModel;

    public function execute($request)
    {
        $currentTenant = $this->getTenantModel()::current();
        $user = (new ($this->getUserClass()))::create($request->userProps());
        event(new Registered($user));
        Auth::guard($currentTenant->getGuard())->login($user);
    }
}
