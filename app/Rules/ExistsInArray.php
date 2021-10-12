<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExistsInArray extends InArrayFromDB
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return array_search($value, $this->array) !== false;
    }
}
