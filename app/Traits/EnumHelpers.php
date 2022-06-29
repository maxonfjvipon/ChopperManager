<?php

namespace App\Traits;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMappedKeyValue;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrValues;

/**
 * Enum helpers
 */
trait EnumHelpers
{
    public static function getDescriptions($values = null): array
    {
        if ($values === null) {
            return array_values(self::asSelectArray());
        }

        return collect(is_array($values) ? $values : func_get_args())
            ->map(fn($value) => static::getDescription($value))
            ->toArray();
    }

    /**
     * @throws Exception
     */
    public static function asArrayForSelect(): array
    {
        return ArrValues::new(
            ArrMappedKeyValue::new(
                self::asSelectArray(),
                fn($value, $description) => [
                    'id' => $value,
                    'name' => $description
                ]
            )
        )->asArray();
    }

    /**
     * @throws Exception
     */
    public static function getValueByDescription(string $description): int|string
    {
        $value = array_search($description, self::asSelectArray());
        if (!$value) {
            throw new \Exception("Description does not exists");
        }
        return $value;
    }
}
