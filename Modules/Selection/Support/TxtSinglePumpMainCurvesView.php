<?php

namespace Modules\Selection\Support;

use App\Support\TxtView;
use Illuminate\Support\Facades\Auth;
use Maxonfjvipon\Elegant_Elephant\Text;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Support\Performance\PpHMax;
use Modules\Selection\Support\Performance\PumpPerfLine;
use Modules\Selection\Traits\AxisStep;

/**
 * Single pump main curves view
 */
final class TxtSinglePumpMainCurvesView implements Text
{
    use AxisStep;

    /**
     * Ctor.
     * @param Pump $pump
     */
    public function __construct(private Pump $pump) {}

    /**
     * @inheritDoc
     */
    public function asString(): string
    {
        return (new TxtView(
            'pump::pump_performance',
            [
                'performance_lines' => [$performanceLine = (new PumpPerfLine($this->pump))->asArray()],
                'dx' => 900 / ($xMax = $performanceLine[count($performanceLine) - 1]['x']),
                'dy' => 400 / ($yMax = (new PpHMax($this->pump->performance()))->asNumber()),
                'x_axis_step' => $this->axisStep($xMax),
                'y_axis_step' => $this->axisStep($yMax),
                'dots_data' => [$this->pump->performance()->asArrayAt(1)]
            ]
        ))->asString();
    }
}
