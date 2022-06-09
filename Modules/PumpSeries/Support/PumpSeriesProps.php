<?php

namespace Modules\PumpSeries\Support;

use JetBrains\PhpStorm\Pure;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\PumpSeries\Transformers\RcPumpSeriesProps;

final class PumpSeriesProps implements Arrayable
{
    /**
     * @inheritDoc
     */
    #[Pure] public function asArray(): array
    {
        return ['pump_series_props' => new RcPumpSeriesProps(null)];
    }
}
