<?php

namespace Modules\Components\Actions;

use App\Interfaces\RsAction;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrayableOf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;

/**i
 * Components action.
 */
class AcComponents extends ArrEnvelope
{
    /**
     * Ctor.
     *
     * @param array|Arrayable $filterData
     * @param string $componentsName
     * @param array|Arrayable $components
     */
    public function __construct(
        array|Arrayable $filterData,
        string          $componentsName,
        array|Arrayable $components
    )
    {
        parent::__construct(
            new ArrayableOf([
                'filter_data' => $filterData,
                $componentsName => $components
            ])
        );
    }
}
