<?php


namespace Modules\PumpProducer\Actions;

use Modules\PumpProducer\Entities\User;

class RegisterUserAction extends \Modules\Auth\Actions\RegisterUserAction
{
    protected function getUserClass(): string
    {
        return new User;
    }
}
