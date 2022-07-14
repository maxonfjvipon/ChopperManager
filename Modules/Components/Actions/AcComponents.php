<?php

namespace Modules\Components\Actions;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;

/**i
 * Components action.
 */
class AcComponents extends ArrEnvelope
{
    /**
     * Ctor.
     */
    public function __construct(
        array|Arrayable $filterData,
        string $componentsName,
        array|Arrayable $components
    ) {
        parent::__construct(
            new ArrMerged(
                new ArrObject(
                    'filter_data',
                    $filterData,
                ),
                new ArrObject(
                    $componentsName,
                    $components
                )
            )
        );
    }
}
