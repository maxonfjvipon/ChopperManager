<?php

namespace Modules\Selection\Support;

use Illuminate\Database\Eloquent\Collection;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\Components\Entities\Collector;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Http\Requests\RqMakeSelection;

/**
 * Collectors for {@see SelectionType::Auto} selection
 */
final class ArrCollectorsForAutoSelection extends ArrEnvelope
{
    /**
     * Ctor.
     * @param RqMakeSelection $request
     * @param Arrayable $dnsMaterials
     * @param Pump $pump
     * @param int $pumpsCount
     * @param int $minDn
     */
    public function __construct(
        RqMakeSelection $request,
        Arrayable       $dnsMaterials,
        Pump            $pump,
        int             $pumpsCount,
        int             $minDn,
    )
    {
        parent::__construct(
            new ArrMapped(
                $dnsMaterials,
                fn(array $dnMaterial) => Collector::forSelection(
                    $request->station_type,
                    $pump,
                    $pumpsCount,
                    $dnMaterial,
                    $minDn,
                )),
        );
    }
}
