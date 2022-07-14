<?php

namespace Modules\PumpSeries\Actions;

use App\Models\Enums\Country;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Transformers\RcPumpBrand;

/**
 * Edit pump brand action.
 */
final class AcCreateOrEditPumpBrand extends ArrEnvelope
{
    /**
     * Ctor.
     *
     * @throws Exception
     */
    public function __construct(private ?PumpBrand $brand = null)
    {
        parent::__construct(
            new ArrMerged(
                ['countries' => Country::asArrayForSelect()],
                new ArrIf(
                    (bool) $this->brand,
                    fn () => ['brand' => new RcPumpBrand($this->brand)]
                )
            )
        );
    }
}
