<?php

namespace Modules\Selection\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\Components\Entities\Collector;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Http\Requests\RqMakeSelection;

/**
 * Collectors for {@see SelectionType::Auto} selection.
 */
final class ArrCollectorsForAutoSelection extends ArrEnvelope
{
    /**
     * Ctor.
     */
    public function __construct(
        RqMakeSelection $request,
        Arrayable $dnsMaterials,
        Pump $pump,
        int $pumpsCount,
        int $minDn,
    ) {
        parent::__construct(
            new ArrMapped(
                $dnsMaterials,
                fn (array $dnMaterial) => Collector::forSelection(
                    $request->station_type,
                    $pump,
                    $pumpsCount,
                    $dnMaterial,
                    $minDn,
                )),
        );
    }
}
