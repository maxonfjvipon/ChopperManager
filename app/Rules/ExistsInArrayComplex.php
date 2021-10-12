<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExistsInArrayComplex extends InArrayFromDB
{
    protected string $separator;
    private array $invalidValues = [];

    /**
     * Create a new rule instance.
     *
     * @param $array
     * @param $separator
     */
    public function __construct($array, $separator)
    {
        parent::__construct($array);
        $this->separator = $separator;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $passes = true;
        foreach (explode($this->separator, $value) as $_value) {
            if (array_search((int)trim($_value), $this->array) === false) {
                $this->invalidValues[] = $_value;
                $passes = false;
            }
        }
        return $passes;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('validation.pump_import.in_array_complex') . ": " . implode(", ", $this->invalidValues);
    }
}
