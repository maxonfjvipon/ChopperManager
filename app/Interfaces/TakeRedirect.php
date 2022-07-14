<?php

namespace App\Interfaces;

use Illuminate\Http\RedirectResponse;

/**
 * For redirect endpoints.
 */
interface TakeRedirect extends Take
{
    public function redirect(): RedirectResponse;
}
