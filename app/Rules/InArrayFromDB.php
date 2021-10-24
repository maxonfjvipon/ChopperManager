<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class InArrayFromDB implements Rule
{
    protected array $array;

    /**
     * Create a new rule instance.
     *
     * @param $array
     */
    public function __construct($array)
    {
        $this->array = $array;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('validation.import.in_array');
    }

    public function passes($attribute, $value): bool
    {
        return true;
    }
}
