<?php


namespace Modules\User\Http\Requests\Interfaces;

interface WithAvailableProps
{
    public function availableProps(): array;
}
