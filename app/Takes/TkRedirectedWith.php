<?php

namespace App\Takes;

use App\Support\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\Takes\TkRedirectedTest;

/**
 * Redirect endpoint with.
 * @package App\Takes
 * @see TkRedirectedTest
 */
final class TkRedirectedWith implements Take, TakeRedirect
{
    /**
     * @var TakeRedirect
     */
    private TakeRedirect $origin;

    /**
     * @var string|array $key
     */
    private array|string $key;

    /**
     * @var mixed
     */
    private mixed $value;

    /**
     * Ctor.
     * @param $key
     * @param $value
     * @param TakeRedirect $origin
     */
    public function __construct($key, $value, TakeRedirect $origin)
    {
        $this->key = $key;
        $this->value = $value;
        $this->origin = $origin;
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
        return $this->origin->redirect()->with($this->key, $this->value);
    }
}
