<?php


namespace Modules\AdminPanel\Traits;

use Modules\Auth\Http\Controllers\LoginController;

trait HasControllerInModule
{
    abstract function getModuleName();

    public function getControllerClass($name): string
    {
        return "Modules\\" . $this->getModuleName() . "\\Http\\Controllers\\" . $name;
    }
}
