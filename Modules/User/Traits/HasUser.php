<?php

namespace Modules\User\Traits;

use App\Traits\UsesClassesFromModule;
use Modules\User\Entities\User;

trait HasUser
{
    use UsesClassesFromModule;

    abstract public function getUserClass(): string;

    public function getUserModel()
    {
        return $this->tryNewClassFromModule(
            $this->getUserClass(),
            User::class
        );
    }
}
