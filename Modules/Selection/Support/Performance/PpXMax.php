<?php

namespace Modules\Selection\Support\Performance;

use Maxonfjvipon\Elegant_Elephant\Numerable\MaxOf;
use Maxonfjvipon\Elegant_Elephant\Numerable\NumEnvelope;
use Modules\Selection\Traits\AxisStep;

/**
 * Max X from pump performance lines.
 */
final class PpXMax extends NumEnvelope
{
    use AxisStep;

    /**
     * Ctor.
     *
     * @param array      $lines
     * @param float|null $flow
     */
    public function __construct(private array $lines, private ?float $flow = 0)
    {
        $lastLine = $this->lines[count($this->lines) - 1];
        parent::__construct(
            new MaxOf(
                $lastLine[count($lastLine) - 1]['x'],
                $this->flow + 2 * $this->axisStep($this->flow)
            )
        );
    }
}
