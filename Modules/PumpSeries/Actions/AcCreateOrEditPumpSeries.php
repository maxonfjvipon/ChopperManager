<?php

namespace Modules\PumpSeries\Actions;

use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\PumpSeries\Transformers\RcPumpSeries;
use Modules\PumpSeries\Transformers\RcPumpSeriesProps;

/**
 * Create or edit pump series action.
 */
final class AcCreateOrEditPumpSeries extends ArrEnvelope
{
    /**
     * Ctor.
     *
     * @param PumpSeries|null $pumpSeries
     */
    public function __construct(private ?PumpSeries $pumpSeries = null)
    {
        parent::__construct(
            new ArrMerged(
                ['pump_series_props' => new RcPumpSeriesProps()],
                new ArrIf(
                    (bool) $this->pumpSeries,
                    fn () => ['series' => new RcPumpSeries($this->pumpSeries)]
                )
            )
        );
    }
}
