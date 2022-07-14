<?php

namespace Modules\Pump\Rules;

/**
 * Pump performance regex rule.
 */
final class PumpPerformanceRegex
{
    public function __toString(): string
    {
        return 'regex:/^\s*\d+([,.]\d+)?\s{1}\d+([,.]\d+)?((\s{1}\d+([,.]\d+)?){2}){2,29}\s*$/';
    }
}
