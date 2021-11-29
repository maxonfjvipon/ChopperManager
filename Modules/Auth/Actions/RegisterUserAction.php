<?php

namespace Modules\Auth\Actions;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Http\Requests\UserRegisterable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class RegisterUserAction
{
    use UsesTenantModel;

    public function execute(UserRegisterable $request)
    {
        $currentTenant = $this->getTenantModel()::current();
        $user = ($currentTenant->getUserModel())::create($request->userProps());
        event(new Registered($user));
        Auth::guard($currentTenant->getGuard())->login($user);
    }
}
