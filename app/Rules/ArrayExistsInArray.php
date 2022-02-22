<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use JetBrains\PhpStorm\Pure;

class ArrayExistsInArray implements Rule
{
    // TODO: make good
    private array $searchArray;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($array)
    {
        $this->searchArray = $array;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    #[Pure] public function passes($attribute, $value): bool
    {
        foreach ($value as $item) {
            if (!in_array($item, $this->searchArray)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The array does not contain some of the values";
    }
}
