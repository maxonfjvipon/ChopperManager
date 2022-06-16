<?php

namespace Modules\Selection\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Logical\Conjunction;
use Maxonfjvipon\Elegant_Elephant\Logical\KeyExists;
use Maxonfjvipon\Elegant_Elephant\Text;
use Maxonfjvipon\Elegant_Elephant\Text\TxtIf;
use Maxonfjvipon\Elegant_Elephant\Text\TxtJoined;

/**
 * Cost structure as string
 */
final class TxtCostStructure implements Text
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
        return (new TxtJoined(
            "Насос: " . $structure['pump'],
            "\nНасосы: " . $structure['pumps'],
            "\nСистема управления: " . $structure['control_system'],
            "\nРама: " . $structure['chassis'],
            "\nАрматура: " . $structure['armature'],
            "\nВходной коллектор: " . $structure['input_collector'],
            "\nВыходной коллектор: " . $structure['output_collector'],
            "\nРабота по сборке: " . $structure["job"],
            new TxtIf(
                new Conjunction(
                    new KeyExists("jockey_pump", $structure),
                    new KeyExists("jockey_chassis", $structure)
                ),
                fn() => new TxtJoined(
                    "\nЖокей насос: " . $structure['jockey_pump'],
                    "\nРама жокей насоса: " . $structure['jockey_chassis']
                )
            )
        ))->asString();
    }
}
