<?php


namespace Modules\Auth\Http\Requests;

interface UserRegisterable
{
    public function userProps(): array;
}
