<?php

namespace Modules\Pump\Support\LoadedPumps;

use Illuminate\Database\Eloquent\Builder;

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
