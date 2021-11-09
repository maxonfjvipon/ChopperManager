<?php


namespace Modules\AdminPanel\Traits;

use Modules\User\Http\Controllers\UsersController;

trait HasUsersController
{
    public function getUsersController()
    {
        return $this->existedClassInModule($this->getControllerClass("UsersController"), UsersController::class);

    }
}
