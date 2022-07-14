<?php

namespace App\Interfaces;

use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Invokable endpoint.
 */
interface Endpoint
{
    public function __invoke(): Responsable|Response;
}
