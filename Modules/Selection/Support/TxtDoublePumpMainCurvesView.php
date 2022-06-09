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
 * Double pump main curves view
 */
final class TxtDoublePumpMainCurvesView implements Text
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
        $standbyPerfLine = (new PumpPerfLine($this->pump, 1))->asArray();
        $peakPerfLine = (new PumpPerfLine($this->pump, 2))->asArray();
        $xMax = $peakPerfLine[count($peakPerfLine) - 1]['x'];
        $yMax = (new PpHMax($this->pump->performance()))->asNumber();
        return (new TxtView(
            'pump::pump_performance',
            [
                'performance_lines' => [$standbyPerfLine, $peakPerfLine],
                'dx' => 900 / $xMax,
                'dy' => 400 / $yMax,
                'x_axis_step' => $this->axisStep($xMax),
                'y_axis_step' => $this->axisStep($yMax),
                'dots_data' => Auth::user()->isAdmin()
                    ? [
                        $this->pump->performance()->asArrayAt(1),
                        $this->pump->performance()->asArrayAt(2)
                    ] : []
            ]
        ))->asString();
    }
}
