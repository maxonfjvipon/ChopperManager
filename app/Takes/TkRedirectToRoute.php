<?php

namespace App\Takes;

use App\Interfaces\TakeRedirect;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect endpoint.
 *
 * @see TkRedirectedTest
 */
final class TkRedirectToRoute implements TakeRedirect
{
    private string $route;

    /**
     * @var array;
     */
    private array $params;

    /**
     * Ctor.
     *
     * @param  mixed  ...$params
     */
    public function __construct(string $route, ...$params)
    {
        $this->route = $route;
        $this->params = $params;
    }

    /**
     * {@inheritDoc}
     */
    public function act(Request $request = null): Responsable|Response
    {
        return $this->redirect();
    }

    public function redirect(): RedirectResponse
    {
        return Redirect::route($this->route, ...$this->params);
    }
}
