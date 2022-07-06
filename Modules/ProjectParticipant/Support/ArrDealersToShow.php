<?php

namespace Modules\ProjectParticipant\Support;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\ProjectParticipant\Entities\Dealer;

/**
 * Dealers to show as Arrayable.
 */
final class ArrDealersToShow extends ArrEnvelope
{
    /**
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct(
            new ArrMapped(
                Dealer::allOrCached()->load('area')->all(),
                fn(Dealer $dealer) => $dealer->asArray()
            )
        );
    }
}
