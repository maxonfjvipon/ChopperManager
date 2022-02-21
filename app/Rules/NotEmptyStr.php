<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NotEmptyStr implements Rule
{

    public function passes($attribute, $value): bool
    {
        return $value != '';
    }

    public function message(): string
    {
        return "The value is empty";
    }
}
