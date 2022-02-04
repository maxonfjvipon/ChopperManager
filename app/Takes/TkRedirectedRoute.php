<?php

namespace App\Takes;

use App\Support\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect endpoint.
 * @package App\Takes
 */
final class TkRedirectedRoute implements Take, TakeRedirect
{
    /**
     * @var string $route
     */
    private string $route;

    /**
     * @var array $params;
     */
    private array $params;

    /**
     * Ctor wrap.
     * @param string $route
     * @param mixed ...$params
     * @return TkRedirectedRoute
     */
    public static function new(string $route, ...$params): TkRedirectedRoute
    {
        return new self($route, ...$params);
    }

    /**
     * Ctor.
     * @param string $route
     * @param mixed ...$params
     */
    public function __construct(string $route, ...$params)
    {
        $this->route = $route;
        $this->params = $params;
    }

    /**
     * @inheritDoc
     */
    public function act(Request $request = null): Responsable|Response
    {
        return $this->redirect();
    }

    /**
     * @return RedirectResponse
     */
    public function redirect(): RedirectResponse
    {
         return Redirect::route($this->route, ...$this->params);
    }
}
