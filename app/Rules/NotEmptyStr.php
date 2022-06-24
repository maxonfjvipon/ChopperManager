<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Not empty string rule.
 */
final class NotEmptyStr implements Rule
{
    /**
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return $value != '';
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return "The value is empty";
    }
}
