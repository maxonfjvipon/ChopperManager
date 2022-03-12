<?php

namespace Modules\Selection\Support\Performance;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Pump\Entities\Pump;

/**
 * Pump performance lines
 */
final class PumpPerfLines implements Arrayable
{
    /**
     * @var int $position
     */
    private int $count;

    /**
     * @var Pump $pump
     */
    private Pump $pump;

    /**
     * Ctor.
     * @param Pump $pump
     * @param int $count
     */
    public function __construct(Pump $pump, int $count = 1)
    {
        $this->pump = $pump;
        $this->count = $count;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function asArray(): array
    {
        $lines = [];
        for ($i = 1; $i <= $this->count; ++$i) {
            $lines[] = (new PumpPerfLine($this->pump, $i))->asArray();
        }
        return $lines;
    }
}
