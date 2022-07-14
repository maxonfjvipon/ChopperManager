<?php

namespace Modules\Selection\Support;

use App\Support\TxtView;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Text\TxtEnvelope;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Support\Performance\PpHMax;
use Modules\Selection\Support\Performance\PumpPerfLine;
use Modules\Selection\Traits\AxisStep;

/**
 * Single pump main curves view.
 */
final class TxtSinglePumpMainCurvesView extends TxtEnvelope
{
    use AxisStep;

    /**
     * Ctor.
     *
     * @throws Exception
     */
    public function __construct(private Pump $pump)
    {
        parent::__construct(
            TxtView::new(
                'pump::pump_performance',
                [
                    'performance_lines' => [$performanceLine = (new PumpPerfLine($this->pump))->asArray()],
                    'dx' => 900 / ($xMax = $performanceLine[count($performanceLine) - 1]['x']),
                    'dy' => 400 / ($yMax = (new PpHMax($this->pump->performance()))->asNumber()),
                    'x_axis_step' => $this->axisStep($xMax),
                    'y_axis_step' => $this->axisStep($yMax),
                    'dots_data' => [$this->pump->performance()->asArrayAt(1)],
                ]
            )
        );
    }
}
