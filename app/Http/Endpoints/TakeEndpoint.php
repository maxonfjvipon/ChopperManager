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
 * Invokable endpoint that make {@see Take} act
 */
abstract class TakeEndpoint implements Endpoint
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var Request $request
     */
    private Request $request;

    /**
     * Ctor.
     * @param Take $take
     * @param Request|null $request
     */
    public function __construct(
        private Take $take,
        ?Request     $request = null,
    )
    {
        $this->request = $request ?? \request();
    }

    /**
     * @return Responsable|Response
     */
    public function __invoke(): Responsable|Response
    {
        return $this->take->act($this->request);
    }
}
