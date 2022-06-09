<?php

namespace Modules\Selection\Support\Performance;

use Maxonfjvipon\Elegant_Elephant\Numerable;
use Maxonfjvipon\Elegant_Elephant\Numerable\MaxOf;
use Modules\Selection\Traits\AxisStep;

/**
 * Max X from pump performance lines
 */
final class PpXMax implements Numerable
{
    use AxisStep;

    /**
     * @var float $flow
     */
    private float $flow;

    /**
     * Ctor.
     * @param array $lines
     * @param float|null $flow
     */
    public function __construct(
        private array $lines, ?float $flow)
    {
        $this->flow = $flow ?? 0;
    }

    /**
     * @inheritDoc
     */
    public function asNumber(): float|int
    {
        $lastLine = $this->lines[count($this->lines) - 1];
        return (new MaxOf(
            $lastLine[count($lastLine) - 1]['x'],
            $this->flow + 2 * $this->axisStep($this->flow)
        ))->asNumber();
    }
}
