<?php


namespace Modules\PumpProducer\Actions;

use Modules\PumpProducer\Entities\PPUser;

class RegisterUserAction extends \Modules\Auth\Actions\RegisterUserAction
{
    protected function getUserClass(): string
    {
        return new PPUser;
    }
}
