<?php

namespace App\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Interfaces\Take;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Endpoint that encapsulate take
 */
final class FakeTakeEndpoint extends Controller
{
    /**
     * @var Take $take
     */
    private Take $take;

    /**
     * Ctor.
     * @param Take $take
     */
    public function __construct(Take $take)
    {
        $this->take = $take;
    }

    /**
     * @return Responsable|Response
     */
    public function __invoke(): Responsable|Response
    {
        return $this->take->act();
    }
}
