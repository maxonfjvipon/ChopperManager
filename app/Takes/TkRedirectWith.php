<?php

namespace App\Takes;

use App\Interfaces\TakeRedirect;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\Takes\TkRedirectedTest;

/**
 * Redirect endpoint with.
 *
 * @see TkRedirectedTest
 */
final class TkRedirectWith implements TakeRedirect
{
    /**
     * Ctor.
     *
     * @param $key
     * @param $value
     */
    public function __construct(private $key, private $value, private TakeRedirect $origin)
    {
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
        return $this->origin->redirect()->with($this->key, $this->value);
    }
}
