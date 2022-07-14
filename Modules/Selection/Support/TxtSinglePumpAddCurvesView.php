<?php

namespace Modules\Selection\Support;

use App\Support\TxtView;
use Maxonfjvipon\Elegant_Elephant\Text;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Support\Performance\PumpPerfLine;
use Modules\Selection\Traits\AxisStep;

/**
 * Single pump additional curves view.
 */
final class TxtSinglePumpAddCurvesView implements Text
{
    use AxisStep;

    /**
     * Ctor.
     *
     * @param Pump $pump
     */
    public function __construct(private Pump $pump)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function asString(): string
    {
        $data = [];
        $xMax = -1;
        foreach (CurveType::cases() as $type) {
            $line = (new PumpPerfLine($this->pump, 1, $type))->asArray();
            $xMax = max($xMax, ...array_map(fn ($point) => $point['x'], $line));
            $data[$type->name] = [
                'lines' => [$line],
                'dy' => 133 / ($yMax = ($max = max(
                            ...array_map(fn ($point) => $point['y'], $line)
                        )) + $max * 0.1),
                'y_axis_step' => $this->axisStep($yMax),
            ];
        }

        return (new TxtView(
            'pump::additional_curves',
            array_merge($data, [
                'dx' => 900 / $xMax,
                'x_axis_step' => $this->axisStep($xMax),
            ])
        ))->asString();
    }
}
