<?php

namespace Modules\Selection\Rules;

use App\Rules\ArrayExistsInArray;
use Illuminate\Contracts\Validation\Rule;
use Modules\Components\Entities\CollectorMaterial;
use Modules\Pump\Entities\DN;

final class DnsMaterialsArray implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (new ArrayExistsInArray(
            array_merge(...array_map(
                fn($dn) => array_map(
                    fn($material) => "$dn $material",
                    CollectorMaterial::getDescriptions()
                ),
                DN::values()
            ))
        ))->passes($attribute, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '"DN Collector" wrong format!';
    }
}
