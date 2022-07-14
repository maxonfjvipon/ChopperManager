<?php

namespace Modules\Selection\Support;

use Maxonfjvipon\Elegant_Elephant\Text\TxtEnvelope;
use Maxonfjvipon\Elegant_Elephant\Text\TxtIf;
use Maxonfjvipon\Elegant_Elephant\Text\TxtJoined;
use Modules\Components\Entities\Collector;
use Modules\Components\Entities\ControlSystem;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\StationType;

/**
 * Pump station name as string.
 */
final class TxtPumpStationName extends TxtEnvelope
{
    /**
     * Ctor.
     */
    public function __construct(
        private ?ControlSystem $controlSystem,
        private int $pumpsCount,
        private Pump $pump,
        private ?Collector $inputCollector,
        private ?Pump $jockeyPump = null,
        private bool $forPdf = false
    ) {
        parent::__construct(
            new TxtJoined(
                new TxtIf(
                    $this->forPdf,
                    fn () => 'BPE PumpMaster '.$this->controlSystem?->type->station_type->key.' '
                ),
                $this->controlSystem?->type->name ?? '?',
                ' '.$this->pumpsCount,
                ' '.$this->pump->series->name,
                ' '.$this->pump->name,
                new TxtIf(
                    (bool) $this->jockeyPump,
                    fn () => new TxtJoined(
                        ' +',
                        ' '.$this->jockeyPump->series->name,
                        ' '.$this->jockeyPump->name
                    )
                ),
                new TxtIf(
                    (bool) $this->controlSystem?->type->station_type->is(StationType::AF),
                    fn () => new TxtJoined(
                        new TxtIf((bool) $this->controlSystem?->avr->value, ' АВР'),
                        new TxtIf(
                            $this->controlSystem?->gate_valves_count > 0,
                            fn () => new TxtJoined(' ЭЗ', $this->controlSystem?->gate_valves_count)
                        ),
                        new TxtIf((bool) $this->controlSystem?->kkv->value, ' ККВ'),
                        new TxtIf((bool) $this->controlSystem?->on_street->value, ' УИ')
                    )
                ),
                ' ДУ',
                ' '.($this->inputCollector?->dn_common ?? '?').' ',
                $this->inputCollector?->material->description ?? '',
            )
        );
    }
}
