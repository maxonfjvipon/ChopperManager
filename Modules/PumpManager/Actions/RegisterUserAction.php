<?php

namespace Modules\PumpManager\Actions;

use Modules\PumpManager\Entities\User;

class RegisterUserAction extends \Modules\Auth\Actions\RegisterUserAction
{
    public function getUserClass(): string
    {
        return User::class;
    }
}
