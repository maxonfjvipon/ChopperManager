<?php


namespace Modules\AdminPanel\Traits;


use Modules\Pump\Http\Controllers\PumpBrandsController;

trait HasPumpBrandsControllerInModule
{
    public function getPumpBrandsController()
    {
        return $this->existedClassInModule($this->getControllerClass("PumpBrandsController"), PumpBrandsController::class);
    }
}
