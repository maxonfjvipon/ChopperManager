<?php

namespace App\Interfaces;

use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Invokable endpoint.
 */
interface Endpoint
{
    /**
     * @return Responsable|Response
     */
    public function __invoke(): Responsable|Response;
}
