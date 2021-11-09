<?php

namespace Modules\AdminPanel\Traits;

use Modules\Auth\Http\Controllers\EmailVerificationController;
use Modules\Auth\Http\Controllers\LoginController;
use Modules\Auth\Http\Controllers\RegisterController;

trait HasAuthControllersInModule
{
    public function getLoginController()
    {
        return $this->existedClassInModule($this->getControllerClass("LoginController"), LoginController::class);
    }

    public function getRegisterController()
    {
        return $this->existedClassInModule($this->getControllerClass("RegisterController"), RegisterController::class);
    }

    public function getEmailVerificationController()
    {
        return $this->existedClassInModule($this->getControllerClass("EmailVerificationController"), EmailVerificationController::class);
    }

}
