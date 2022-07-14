<?php

namespace Modules\Selection\Support;

use Maxonfjvipon\Elegant_Elephant\Text;
use Maxonfjvipon\Elegant_Elephant\Text\TxtEnvelope;
use Maxonfjvipon\Elegant_Elephant\Text\TxtJoined;
use Modules\Components\Entities\ControlSystem;

/**
 * Pump station full name.
 */
final class TxtPumpStationFullName extends TxtEnvelope
{
    /**
     * Ctor.
     *
     * @param  Text|string  $pumpStationName
     * @param  ControlSystem|null  $controlSystem
     */
    public function __construct(Text|string $pumpStationName, ?ControlSystem $controlSystem)
    {
        parent::__construct(
            new TxtJoined(
                'BPE PumpMaster '.$controlSystem?->type->station_type->key.' ',
                $pumpStationName
            )
        );
    }
}
