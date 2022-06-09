<?php

namespace App\Takes;

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
final class TkRedirectWith implements TakeRedirect
{
    /**
     * Ctor.
     * @param $key
     * @param $value
     * @param TakeRedirect $origin
     */
    public function __construct(private $key, private $value, private TakeRedirect $origin)
    {
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
