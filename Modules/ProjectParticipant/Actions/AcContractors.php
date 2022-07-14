<?php

namespace Modules\ProjectParticipant\Actions;

use App\Support\ArrForFiltering;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrSticky;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrUnique;
use Modules\ProjectParticipant\Entities\Contractor;

/**
 * Contractors action.
 */
final class AcContractors extends ArrEnvelope
{
    /**
     * Ctor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct(
            new ArrMerged(
                new ArrObject(
                    'contractors',
                    $contractors = new ArrSticky(
                        new ArrMapped(
                            Contractor::allOrCached()
                                ->load('area')
                                ->all(),
                            fn(Contractor $contractor) => $contractor->asArray()
                        )
                    )
                ),
                new ArrObject(
                    'filter_data',
                    new ArrForFiltering(
                        ['areas' => new ArrUnique(
                            new ArrMapped(
                                $contractors,
                                fn(array $contractor) => $contractor['area']
                            )
                        )]
                    )
                )
            )
        );
    }
}
