<?php

namespace Modules\Selection\Services;

use App\Services\ModuleResourceServiceInterface;
use Modules\Selection\Entities\SinglePumpSelection;

interface SinglePumpSelectionServiceInterface extends ModuleResourceServiceInterface
{
    public function __show(SinglePumpSelection $selection);

    public function __index($project_id);

    public function __create($project_id);
}
