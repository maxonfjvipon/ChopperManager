<?php

namespace Modules\Selection\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\Pure;

/**
 * @property array<array> $added_stations
 */
abstract class RqStoreSelection extends RqDetermineSelection
{
    private string $separator = ",";

    #[Pure] protected function imploded($array): ?string
    {
        return $array ? implode($this->separator, $array) : null;
    }

    abstract public function selectionProps(): array;
}
