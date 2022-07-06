<?php

namespace Modules\ProjectParticipant\Actions;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrSticky;
use Modules\ProjectParticipant\Support\ArrDealersFilterData;
use Modules\ProjectParticipant\Support\ArrDealersToShow;

/**
 * Dealers action.
 */
final class AcDealers extends ArrEnvelope
{
    /**
     * Ctor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct(
            new ArrMerged(
                new ArrObject(
                    'dealers',
                    $dealers = new ArrSticky(
                        new ArrDealersToShow()
                    )
                ),
                new ArrDealersFilterData($dealers)
            )
        );
    }
}
