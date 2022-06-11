<?php

namespace Modules\Selection\Support;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Text;
use Maxonfjvipon\Elegant_Elephant\Text\TxtFromCallback;
use Maxonfjvipon\Elegant_Elephant\Text\TxtIf;
use Maxonfjvipon\Elegant_Elephant\Text\TxtImploded;
use Modules\Components\Entities\Collector;
use Modules\Components\Entities\ControlSystem;
use Modules\Pump\Entities\Pump;

final class TxtPumpStationName implements Text
{
    public function __construct(
        private ?ControlSystem $controlSystem,
        private int            $pumpsCount,
        private Pump           $pump,
        private ?Collector     $inputCollector,
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
                new TxtFromCallback(
                    fn() => "BPE PumpMaster " . $this->controlSystem?->type->station_type->key
                )
            ),
            $this->controlSystem?->type->name ?? "?",
            $this->pumpsCount,
            $this->pump->series->name,
            $this->pump->name,
            "ДУ",
            $this->inputCollector?->dn_common ?? "?",
            $this->inputCollector?->material->description ?? ""
        ))->asString();
    }
}
