<?php


namespace App\Traits;

trait HasFilterData
{
    public function asFilterData($data): array
    {
        return array_map(fn($item) => array_map(fn($value) => [
            'text' => $value,
            'value' => $value,
        ], $item), $data);
    }
}
