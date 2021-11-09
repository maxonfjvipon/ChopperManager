<?php


namespace Modules\AdminPanel\Traits;

use Modules\Pump\Http\Controllers\PumpSeriesController;

trait HasSeriesControllersInModule
{
    public function getPumpSeriesController()
    {
        return $this->existedClassInModule($this->getControllerClass("PumpSeriesController"), PumpSeriesController::class);
    }
}
