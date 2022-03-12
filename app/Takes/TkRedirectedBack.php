<?php

namespace App\Takes;

use App\Support\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\Takes\TkRedirectedTest;

/**
 * Redirect back endpoint.
 * @package App\Takes
 * @see TkRedirectedTest
 */
final class TkRedirectedBack implements Take, TakeRedirect
{
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
        return Redirect::back();
    }
}
