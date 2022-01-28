<?php


namespace App\Endpoints;


use App\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect endpoint.
 * @package App\Endpoints
 */
class RedirectRouteEndpoint implements Renderable
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
    public function render(Request $request = null): Responsable|Response
    {
        return Redirect::route($this->route, ...$this->params);
    }
}
