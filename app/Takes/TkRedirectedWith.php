<?php

namespace App\Takes;

use App\Support\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect endpoint with.
 * @package App\Takes
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
     * Ctor wrap.
     * @param $key
     * @param $value
     * @param TakeRedirect $origin
     * @return TkRedirectedWith
     */
    public static function new($key, $value, TakeRedirect $origin)
    {
        return new self($key, $value, $origin);
    }

    /**
     * Ctor.
     * @param $key
     * @param $value
     * @param $origin
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
