<?php

namespace Modules\PumpSeries\Support;

use App\Support\TenantStorage;
use JetBrains\PhpStorm\Pure;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\PumpSeries\Transformers\RcPumpSeries;

/**
 * Pump series as resource.
 */
final class PumpSeriesAsResource implements Arrayable
{
    /**
     * @var PumpSeries $series
     */
    private PumpSeries $series;

    /**
     * @param PumpSeries $pumpSeries
     */
    public function __construct(PumpSeries $pumpSeries)
    {
        $this->series = $pumpSeries;
    }

    /**
     * @inheritDoc
     */
    #[Pure] public function asArray(): array
    {
        return ['series' => new RcPumpSeries($this->series)];
    }
}
