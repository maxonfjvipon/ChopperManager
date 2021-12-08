<?php


namespace Modules\User\Http\Requests\Interfaces;

interface WithUserProps
{
    public function userProps(): array;
}
