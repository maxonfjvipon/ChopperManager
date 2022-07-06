<?php

namespace Modules\ProjectParticipant\Support;

use App\Support\ArrForFiltering;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrUnique;
use Modules\PumpSeries\Entities\PumpSeries;

/**
 * Dealers filter data as {@see Arrayable}
 */
final class ArrDealersFilterData extends ArrEnvelope
{
    /**
     * Ctor.
     * @param Arrayable $dealers
     */
    public function __construct(Arrayable $dealers)
    {
        parent::__construct(
            new ArrObject(
                'filter_data',
                new ArrForFiltering([
                    'areas' => new ArrUnique(
                        new ArrMapped(
                            $dealers,
                            fn(array $dealer) => $dealer['area']
                        )
                    )
                ])
            )
        );
    }
}
