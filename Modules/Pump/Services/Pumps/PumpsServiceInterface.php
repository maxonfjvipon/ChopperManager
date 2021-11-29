<?php


namespace Modules\Pump\Services\Pumps;

use App\Services\ModuleResourceServiceInterface;
use Modules\Pump\Entities\Pump;

interface PumpsServiceInterface extends ModuleResourceServiceInterface
{
    public function __index();

    public function __show(Pump $pump);
}
