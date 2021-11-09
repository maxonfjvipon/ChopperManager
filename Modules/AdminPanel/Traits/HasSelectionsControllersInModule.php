<?php


namespace Modules\AdminPanel\Traits;

use Modules\Core\Http\Controllers\SelectionsController;

trait HasSelectionsControllersInModule
{
    public function getSelectionsController()
    {
        return $this->existedClassInModule($this->getControllerClass("SelectionsController"), SelectionsController::class);
    }
}
