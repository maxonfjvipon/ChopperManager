<?php

namespace Modules\Selection\Rules;

use Modules\Components\Entities\CollectorMaterial;

class DnMaterialRegex
{
    /**
     * The name of the rule.
     */
    protected string $rule = 'regex';

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     *
     * @see \Illuminate\Validation\ValidationRuleParser::parseParameters
     */
    public function __toString()
    {
        return $this->rule.":/^\d{2,3}\s{1}("
            .CollectorMaterial::getDescription(CollectorMaterial::Steel)
            .'|'
            .CollectorMaterial::getDescription(CollectorMaterial::AISI).')$/';
    }
}
