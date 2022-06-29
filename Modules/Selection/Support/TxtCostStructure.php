<?php

namespace Modules\Selection\Support;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Logical\Conjunction;
use Maxonfjvipon\Elegant_Elephant\Logical\KeyExists;
use Maxonfjvipon\Elegant_Elephant\Text\TxtEnvelope;
use Maxonfjvipon\Elegant_Elephant\Text\TxtIf;
use Maxonfjvipon\Elegant_Elephant\Text\TxtJoined;

/**
 * Cost structure as string
 */
final class TxtCostStructure extends TxtEnvelope
{
    /**
     * Ctor.
     * @param Arrayable $structure
     * @throws Exception
     */
    public function __construct(
        private Arrayable $structure
    )
    {
        parent::__construct(
            new TxtJoined(
                "Насос: " . ($structure = $this->structure->asArray())['pump'],
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
            )
        );
    }
}
