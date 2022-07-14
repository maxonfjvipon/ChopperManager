<?php

namespace App\Interfaces;

/**
 * Invokable action.
 */
interface InvokableAction
{
    public function __invoke(): void;
}
