<?php

namespace Modules\Pump\Support\Pump\LoadedPumps;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;

/**
 * Loaded pumps
 */
interface LoadedPumps
{
    /**
     * @return Builder
     */
    public function loaded(): Builder;
}
