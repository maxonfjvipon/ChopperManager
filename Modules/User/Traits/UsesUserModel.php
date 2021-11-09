<?php

namespace Modules\User\Traits;

trait UsesUserModel
{
    abstract public function getUserClass(): string;
}
