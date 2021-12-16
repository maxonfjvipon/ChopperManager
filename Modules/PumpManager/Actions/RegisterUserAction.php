<?php

namespace Modules\PumpManager\Actions;

use Modules\PumpManager\Entities\PMUser;

class RegisterUserAction extends \Modules\Auth\Actions\RegisterUserAction
{
    public function getUserClass(): string
    {
        return PMUser::class;
    }
}
