<?php

namespace Modules\Pump\Actions;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Transformers\RcPumpToShow;

/**
 * Show pump action.
 */
final class AcShowPump implements Arrayable
{
    /**
     * Ctor.
     *
     * @param Pump $pump
     */
    public function __construct(private Pump $pump)
    {
    }

    public function asArray(): array
    {
        return ['pump' => new RcPumpToShow($this->pump)];
    }
}
