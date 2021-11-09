<?php

namespace Modules\Auth\Traits;

trait CanRegisterUser
{
    abstract public function userProps(): array;

    public function authorize(): bool
    {
        return true;
    }
}
