<?php

namespace App\Takes;

use App\Support\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect back endpoint.
 * @package App\Takes
 */
final class TkRedirectedBack implements Take, TakeRedirect
{
    /**
     * Ctor wrap.
     * @return TkRedirectedBack
     */
    public static function new(): TkRedirectedBack
    {
        return new self();
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
        return Redirect::back();
    }
}
