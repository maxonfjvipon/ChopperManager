<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExistsAsKeyInArray extends InArrayFromDB
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        return array_key_exists($value, $this->array);
    }
}
