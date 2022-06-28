<?php

namespace Modules\Selection\Support;

use Illuminate\Database\Eloquent\Collection;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\Components\Entities\ControlSystem;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\StationType;
use Modules\Selection\Http\Requests\RqMakeSelection;

/**
 * Control systems for selection.
 */
final class ArrControlSystemForSelection extends ArrEnvelope
{
    /**
     * Ctor.
     * @param RqMakeSelection $request
     * @param Pump $pump
     * @param int $pumpsCount
     * @param bool|null $isSprinkler
     * @param Collection|null $controlSystems
     */
    public function __construct(
        RqMakeSelection $request,
        Pump            $pump,
        int             $pumpsCount,
        ?bool           $isSprinkler = false,
        ?Collection     $controlSystems = null
    )
    {
        parent::__construct(
            new ArrMapped(
                $request->control_system_type_ids,
                fn(int $controlSystemTypeId) => ($controlSystems ?? ControlSystem::allOrCached())
                    ->where('power', '>=', $pump->power)
                    ->where('pumps_count', $pumpsCount)
                    ->where('type_id', $controlSystemTypeId)
                    ->when(
                        $request->station_type === StationType::getKey(StationType::AF),
                        fn($query) => $query
                            ->where('avr.value', $request->boolean('avr'))
                            ->where('gate_valves_count', $request->gate_valves_count)
                            ->where('kkv.value', $request->boolean('kkv'))
                            ->where('on_street.value', $request->boolean('on_street'))
                            ->where('has_jockey.value', $isSprinkler)
                    )
                    ->sortBy('power')
                    ->first()
            ),
        );
    }
}
