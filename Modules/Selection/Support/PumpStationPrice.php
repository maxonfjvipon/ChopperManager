<?php

namespace Modules\Selection\Support;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Numerable;
use Maxonfjvipon\Elegant_Elephant\Numerable\ArraySum;
use Modules\Components\Entities\Armature;
use Modules\Components\Entities\AssemblyJob;
use Modules\Selection\Entities\StationType;
use Modules\Selection\Http\Requests\RqMakeSelection;
use TypeError;

/**
 * Pump station price
 */
final class PumpStationPrice implements Numerable
{
    /**
     * @param Arrayable $components
     */
    public function __construct(
        private Arrayable $components
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function asNumber(): float|int
    {
        $sum = 0;
        foreach ($this->components->asArray() as $component) {
            if (is_null($component)) {
                return 999999999;
            }
            $sum += $component;
        }
        return $sum;
    }
}
