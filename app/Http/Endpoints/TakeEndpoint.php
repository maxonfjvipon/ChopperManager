<?php

namespace App\Http\Endpoints;

use App\Interfaces\Endpoint;
use App\Interfaces\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Invokable endpoint that make {@see Take} act.
 */
abstract class TakeEndpoint implements Endpoint
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    private Request $request;

    /**
     * Ctor.
     */
    public function __construct(
        private Take $take,
        ?Request $request = null,
    ) {
        $this->request = $request ?? \request();
    }

    public function __invoke(): Responsable|Response
    {
        return $this->take->act($this->request);
    }
}
