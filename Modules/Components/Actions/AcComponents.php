<?php

namespace Modules\Components\Actions;

use JetBrains\PhpStorm\Pure;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;

/**
 * Components action.
 */
class AcComponents
{
    /**
     * Ctor.
     *
     * @param Arrayable $filterData
     * @param string $componentsName
     * @param Arrayable $components
     */
    public function __construct(
        private Arrayable $filterData,
        private string          $componentsName,
        private Arrayable $components
    )
    {
    }

    #[Pure] public function __invoke(): Arrayable
    {
        return new ArrMerged(
            new ArrObject(
                'filter_data',
                $this->filterData,
            ),
            new ArrObject(
                $this->componentsName,
                $this->components,
            )
        );
    }
}
