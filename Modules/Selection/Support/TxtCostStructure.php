<?php

namespace Modules\Selection\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Text;

/**
 * Cost structure as string
 */
class TxtCostStructure implements Text
{
    /**
     * Ctor.
     * @param Arrayable $structure
     */
    public function __construct(
        private Arrayable $structure
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function asString(): string
    {
        $structure = $this->structure->asArray();
        return "Насос: " . $structure['pump']
            . "\nСистема управления: " . $structure['control_system']
            . "\nРама: " . $structure['control_system']
            . "\nАрматура: " . $structure['armature']
            . "\nВходной коллектор: " . $structure['input_collector']
            . "\nВыходной коллектор: " . $structure['output_collector']
            . "\nРабота по сборке: " . $structure["job"];
    }
}
