<?php

namespace Modules\Project\Rules;

/**
 * Project contractor regex rule.
 */
final class ProjectContractorRegex
{
    const REGEX = "/^.{1,512}\?(\d{10}|\d{12})\?[0-9]{2}0{11}$/";

    /**
     * NAME?INN?REGION_KLADR_ID
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'regex:' . self::REGEX;
    }
}
