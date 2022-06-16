<?php

namespace Modules\Selection\Support;

use Closure;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Text;
use Maxonfjvipon\Elegant_Elephant\Text\TxtIf;
use Maxonfjvipon\Elegant_Elephant\Text\TxtImploded;
use Maxonfjvipon\Elegant_Elephant\Text\TxtJoined;
use Modules\Components\Entities\Collector;
use Modules\Components\Entities\ControlSystem;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\StationType;

final class TxtPumpStationName implements Text
{
    public function __construct(
        private ?ControlSystem $controlSystem,
        private int            $pumpsCount,
        private Pump           $pump,
        private ?Collector     $inputCollector,
        private ?Pump          $jockeyPump = null,
        private bool           $forPdf = false
    )
    {
    }

    /**
     * @return string
     * @throws Exception
     */
    public function asString(): string
    {
        return (new TxtImploded(
            " ",
            new TxtIf(
                $this->forPdf,
                fn() => "BPE PumpMaster " . $this->controlSystem?->type->station_type->key
            ),
            $this->controlSystem?->type->name ?? "?",
            $this->pumpsCount,
            $this->pump->series->name,
            $this->pump->name,
            new TxtIf(
                !!$this->jockeyPump,
                fn() => new TxtImploded(
                    " ",
                    "+",
                    $this->jockeyPump->series->name,
                    $this->jockeyPump->name
                )
            ),
            new TxtIf(
                !!$this->controlSystem?->type->station_type->is(StationType::AF),
                fn() => new TxtImploded(
                    " ",
                    new TxtIf(!!$this->controlSystem?->avr->value, "АВР"),
                    new TxtIf(
                        $this->controlSystem?->gate_valves_count > 0,
                        fn() => new TxtJoined("ЭЗ", $this->controlSystem?->gate_valves_count)
                    ),
                    new TxtIf(!!$this->controlSystem?->kkv->value, "ККВ"),
                    new TxtIf(!!$this->controlSystem?->on_street->value, "УИ")
                )
            ),
            "ДУ",
            $this->inputCollector?->dn_common ?? "?",
            $this->inputCollector?->material->description ?? "",
        ))->asString();
    }
}
